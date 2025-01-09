<?php

namespace App\Jobs;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Models\Order;
use App\Models\Product;
use App\Services\Inventory\BundleInventoryManager;
use App\Services\Inventory\InventoryManager;
use App\Services\Payment\StripePaymentService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Stripe\Exception\ApiErrorException;

class CleanupAbandonedOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const ABANDONED_AFTER_MINUTES = 30;

    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(StripePaymentService $stripeService): void
    {
        $cutoffTime = Carbon::now()->subMinutes(self::ABANDONED_AFTER_MINUTES);

        $abandonedOrders = Order::query()
            ->where("created_at", "<", $cutoffTime)
            ->where("status", OrderStatus::PENDING)
            ->where("payment_status", PaymentStatus::PENDING)
            ->whereNotNull("payment_intent_id")
            ->get();

        foreach ($abandonedOrders as $order) {
            try {
                $this->processAbandonedOrder($order, $stripeService);
            } catch (\Exception $e) {
                Log::error("Failed to process abandoned order", [
                    "order_id" => $order->id,
                    "error" => $e->getMessage(),
                ]);
                continue;
            }
        }
    }

    /**
     * Process a single abandoned order
     */
    private function processAbandonedOrder(
        Order $order,
        StripePaymentService $stripeService
    ): void {
        // First, try to cancel the payment intent
        try {
            //TODO: don't forget to cancel other orders from different payment providers
            if ($order->payment_intent_id) {
                $stripeService->cancelPaymentIntent($order->payment_intent_id);
            }
        } catch (ApiErrorException $e) {
            Log::warning("Failed to cancel Stripe payment intent", [
                "order_id" => $order->id,
                "payment_intent_id" => $order->payment_intent_id,
                "error" => $e->getMessage(),
            ]);
        }

        // Release held inventory
        foreach ($order->items as $item) {
            $purchasable = $item->purchasable;
            if ($purchasable->inventory()->shouldTrackQuantity()) {
                // Add the quantity back to inventory
                $purchasable->increment("quantity", $item->quantity);

                // Update stock status
                if ($purchasable instanceof Product) {
                    $inventory = new InventoryManager($purchasable);
                } else {
                    $inventory = new BundleInventoryManager($purchasable);
                }

                $purchasable->update([
                    "stock_status" => $inventory->determineStockStatus(),
                ]);
            }
        }

        // Mark the order as cancelled
        $order->update([
            "status" => OrderStatus::CANCELLED,
            "payment_status" => PaymentStatus::FAILED,
        ]);

        Log::info("Cleaned up abandoned order", ["order_id" => $order->id]);
    }

    /**
     * The job failed to process.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("CleanupAbandonedOrdersJob failed", [
            "error" => $exception->getMessage(),
            "trace" => $exception->getTraceAsString(),
        ]);
    }
}
