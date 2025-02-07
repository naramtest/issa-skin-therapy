<?php

namespace App\Services\Export;

use App\Exports\DHLOrderExport;
use App\Models\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OrderExportService
{
    /**
     * Export a single order to DHL CSV format
     *
     * @param Order $order
     * @return BinaryFileResponse
     */
    public function exportSingleOrderToDHL(Order $order): BinaryFileResponse
    {
        return $this->exportToDHL(collect([$order]));
    }

    /**
     * Export orders to DHL CSV format
     *
     * @param Collection $orders
     * @return BinaryFileResponse
     */
    public function exportToDHL(Collection $orders): BinaryFileResponse
    {
        $timestamp = now()->format("Y-m-d_H-i");
        $filename = "dhl_orders_{$timestamp}.csv";

        return Excel::download(new DHLOrderExport($orders), $filename);
    }
}
