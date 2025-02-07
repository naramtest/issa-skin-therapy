<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\Export\FTPServerService;
use Illuminate\Console\Command;

class PrepareDHLOrders extends Command
{
    protected $signature = "dhl:prepare-orders";
    protected $description = "Prepare orders for DHL Commerce to pick up";

    /**
     * @throws \Exception
     */
    public function handle(FTPServerService $ftpService): void
    {
        // Get orders that need to be prepared for DHL
        $orderIds = Order::query()
            ->whereNull("dhl_exported_at")
            ->whereNotNull("shipping_address_id")
            ->pluck("id")
            ->toArray();

        if (empty($orderIds)) {
            $this->info("No orders to prepare for DHL");
            return;
        }

        $ftpService->exportOrders($orderIds);
        $this->info("Orders prepared for DHL pickup");
    }
}
