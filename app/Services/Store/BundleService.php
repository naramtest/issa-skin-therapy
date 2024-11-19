<?php

namespace App\Services\Store;

use App\Models\Bundle;

class BundleService
{
    //use When Making A Bundle order
    public function processBundlePurchase(
        Bundle $bundle,
        int $quantity = 1
    ): void {
        if ($bundle->bundle_level_stock) {
            $this->updateBundleStock($bundle, $quantity);
        } else {
            $this->updateBundleItemsStock($bundle, $quantity);
        }
    }

    protected function updateBundleStock(Bundle $bundle, int $quantity): void
    {
        if (!$bundle->track_quantity) {
            return;
        }

        $newQuantity = $bundle->quantity - $quantity;
        $bundle->update([
            "quantity" => max(0, $newQuantity),
            "stock_status" => $bundle->determineStockStatus($newQuantity),
        ]);
    }

    protected function updateBundleItemsStock(
        Bundle $bundle,
        int $quantity
    ): void {
        foreach ($bundle->items as $item) {
            $deductQuantity = $item->quantity * $quantity;

            if ($item->product->track_quantity) {
                $newQuantity = $item->product->quantity - $deductQuantity;
                $item->product->update([
                    "quantity" => max(0, $newQuantity),
                    "stock_status" => $item->product->determineStockStatus(
                        $newQuantity
                    ),
                ]);
            }
        }
    }

    public function syncBundleStock(Bundle $bundle): void
    {
        if ($bundle->bundle_level_stock) {
            return;
        }

        $lowestAvailableQuantity = $bundle->items
            ->map(function ($item) {
                if (!$item->product->track_quantity) {
                    return PHP_INT_MAX;
                }
                return floor($item->product->quantity / $item->quantity);
            })
            ->min();

        $bundle->update([
            "quantity" => $lowestAvailableQuantity,
            "stock_status" => $this->determineStockStatus(
                $lowestAvailableQuantity,
                $bundle
            ),
        ]);
    }
}
