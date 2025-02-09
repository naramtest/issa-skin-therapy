<?php

namespace App\Services\Export;

use App\Exports\DHLOrderExport;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class FTPServerService
{
    protected string $inDirectory;
    protected string $outDirectory;
    protected string $historyDirectory;

    public function __construct()
    {
        $this->inDirectory = "IN";
        $this->outDirectory = "OUT";
        $this->historyDirectory = "HISTORY";

        $this->ensureDirectoriesExist();
    }

    protected function ensureDirectoriesExist(): void
    {
        // Create directories for both DHL and team disks
        $disks = ["dhl", "team"];

        foreach ($disks as $disk) {
            $storage = Storage::disk($disk);
            foreach (
                [
                    $this->inDirectory,
                    $this->outDirectory,
                    $this->historyDirectory,
                ]
                as $dir
            ) {
                if (!$storage->exists($dir)) {
                    $storage->makeDirectory($dir);
                }
            }
        }
    }

    /**
     * Place new orders in the IN directory for both DHL and team to pick up
     */
    public function exportOrders(array $orderIds): void
    {
        try {
            $orders = Order::with(["items.purchasable", "shippingAddress"])
                ->whereIn("id", $orderIds)
                ->whereNull("dhl_exported_at")
                ->get();

            if ($orders->isEmpty()) {
                return;
            }

            $filename = "orders_" . now()->format("Y-m-d_His") . ".csv";
            $export = new DHLOrderExport($orders);

            // Store the file in both DHL and team directories
            Excel::store($export, $this->inDirectory . "/" . $filename, "dhl");
            Excel::store($export, $this->inDirectory . "/" . $filename, "team");

            // Mark orders as exported
            $orders->each->update(["dhl_exported_at" => now()]);
        } catch (\Exception $e) {
            Log::error("Failed to export orders", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Process tracking files that DHL has placed in the OUT directory
     * @throws \Exception
     */
    public function processTrackingUpdates(): void
    {
        try {
            $disk = Storage::disk("dhl");
            $files = $disk->files($this->outDirectory);

            foreach ($files as $file) {
                if (!str_ends_with($file, ".csv")) {
                    continue;
                }

                $this->processTrackingFile($file);

                // Move to history after processing
                $disk->move(
                    $file,
                    $this->historyDirectory . "/" . basename($file)
                );

                Log::info("Processed tracking file", [
                    "file" => basename($file),
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Failed to process tracking updates", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    protected function processTrackingFile(string $file): void
    {
        $disk = Storage::disk("dhl");
        $content = $disk->get($file);
        $rows = array_map("str_getcsv", explode("\n", trim($content)));
        $headers = array_shift($rows);

        foreach ($rows as $row) {
            if (empty($row)) {
                continue;
            }

            $data = array_combine($headers, $row);

            try {
                $this->updateOrderTracking($data);
            } catch (\Exception $e) {
                Log::error("Failed to process tracking data", [
                    "error" => $e->getMessage(),
                    "data" => $data,
                    "file" => basename($file),
                ]);
            }
        }
    }

    protected function updateOrderTracking(array $data): void
    {
        $order = Order::where("order_number", $data["Order Number"])->first();

        if (!$order) {
            Log::warning("Order not found for tracking update", [
                "order_number" => $data["Order Number"],
            ]);
            return;
        }

        // Update tracking information
        $order->shippingOrder()->updateOrCreate(
            ["order_id" => $order->id],
            [
                "carrier" => "dhl",
                "tracking_number" => $data["Tracking Number"],
                "status" => "created",
                "shipped_at" => now(),
                "carrier_response" => $data,
            ]
        );

        Log::info("Updated tracking information", [
            "order_number" => $order->order_number,
            "tracking_number" => $data["Tracking Number"],
        ]);
    }

    /**
     * Get the base paths for both DHL and team FTP directories
     */
    public function getBasePaths(): array
    {
        return [
            "dhl" => Storage::disk("dhl")->path(""),
            "team" => Storage::disk("team")->path(""),
        ];
    }

    /**
     * Get all directory paths for both DHL and team
     */
    public function getDirectories(): array
    {
        $paths = [];
        foreach (["dhl", "team"] as $disk) {
            $storage = Storage::disk($disk);
            $paths[$disk] = [
                "base" => $storage->path(""),
                "in" => $storage->path($this->inDirectory),
                "out" => $storage->path($this->outDirectory),
                "history" => $storage->path($this->historyDirectory),
            ];
        }
        return $paths;
    }
}
