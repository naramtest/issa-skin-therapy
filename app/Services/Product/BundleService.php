<?php

namespace App\Services\Product;

use App\Enums\StockStatus;
use App\Models\Bundle;
use App\Models\BundleItem;
use Illuminate\Database\Eloquent\Collection;

class BundleService
{
    public function validateBundleAvailability(Bundle $bundle): bool
    {
        if ($bundle->bundle_level_stock) {
            return $bundle->stock_status->isAvailableForPurchase();
        }

        return $this->checkItemsAvailability($bundle->items);
    }

    public function checkItemsAvailability(Collection $bundleItems): bool
    {
        /** @var BundleItem $item */
        foreach ($bundleItems as $item) {
            if (!$item->product->isAvailableForPurchase()) {
                return false;
            }

            if (
                $item->product->track_quantity &&
                $item->product->quantity < $item->quantity
            ) {
                return false;
            }
        }

        return true;
    }

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
            "stock_status" => $this->determineStockStatus(
                $newQuantity,
                $bundle
            ),
        ]);
    }

    protected function determineStockStatus(int $quantity, $model): StockStatus
    {
        if (!$model->track_quantity) {
            return $model->stock_status;
        }

        return match (true) {
            $quantity <= 0 && $model->allow_backorders
                => StockStatus::BACKORDER,
            $quantity <= 0 => StockStatus::OUT_OF_STOCK,
            $quantity <= $model->low_stock_threshold => StockStatus::LOW_STOCK,
            default => StockStatus::IN_STOCK,
        };
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
                    "stock_status" => $this->determineStockStatus(
                        $newQuantity,
                        $item->product
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
