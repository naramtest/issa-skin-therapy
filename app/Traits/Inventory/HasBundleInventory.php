<?php

namespace App\Traits\Inventory;

use App\Enums\QuantityAction;
use App\Enums\StockStatus;
use App\Models\Bundle;

trait HasBundleInventory
{
    use HasBaseInventory;

    //Use when Showing on the front end
    public function isLowStock(): bool
    {
        if ($this->bundle_level_stock) {
            if (!$this->shouldTrackQuantity()) {
                return $this->stock_status === StockStatus::LOW_STOCK;
            }
            return $this->getCurrentQuantity() <= $this->low_stock_threshold &&
                $this->getCurrentQuantity() > 0;
        }
        return $this->items->contains(function ($item) {
            return $item->product->isLowStock();
        });
    }

    protected function shouldTrackQuantity(): bool
    {
        return $this->bundle_level_stock && $this->track_quantity;
    }

    protected function getCurrentQuantity(): int
    {
        if ($this->bundle_level_stock) {
            return $this->quantity;
        }

        return $this->calculateLowestAvailableQuantity();
    }

    protected function calculateLowestAvailableQuantity(): int
    {
        return $this->items
            ->map(function ($item) {
                return floor($item->product->quantity / $item->quantity);
            })
            ->min();
    }

    public function updateStock(int $quantity, QuantityAction $action): void
    {
        /** @var $this Bundle */

        if (
            $this->bundle_level_stock && !$this->track_quantity or
            !$this->bundle_level_stock
        ) {
            return;
        }

        $newQuantity = match ($action) {
            QuantityAction::ADD => $this->quantity + $quantity,
            QuantityAction::SUBTRACT => $this->quantity - $quantity,
            QuantityAction::SET => $quantity,
        };

        // Ensure quantity doesn't go below zero unless backorders are allowed
        if (!$this->allow_backorders) {
            $newQuantity = max(0, $newQuantity);
        }

        $newStatus = $this->determineStockStatus($newQuantity);

        $this->update([
            "quantity" => $newQuantity,
            "stock_status" => $newStatus,
        ]);
    }

    //Use when Saving a Bundle

    protected function determineStockStatus(int $quantity): StockStatus
    {
        if ($this->bundle_level_stock) {
            if (!$this->shouldTrackQuantity()) {
                return $this->stock_status;
            }

            return match (true) {
                $quantity <= 0 && $this->getAllowBackorders()
                    => StockStatus::BACKORDER,
                $quantity <= 0 => StockStatus::OUT_OF_STOCK,
                $quantity <= $this->low_stock_threshold
                    => StockStatus::LOW_STOCK,
                default => StockStatus::IN_STOCK,
            };
        }

        $lowestAvailableQuantity = $this->calculateLowestAvailableQuantity();

        return match (true) {
            $lowestAvailableQuantity <= 0 => StockStatus::OUT_OF_STOCK,
            $lowestAvailableQuantity <= $this->low_stock_threshold
                => StockStatus::LOW_STOCK,
            default => StockStatus::IN_STOCK,
        };
    }

    protected function getAllowBackorders(): bool
    {
        /** @var $this Bundle */

        return $this->bundle_level_stock && $this->allow_backorders;
    }

    //Use when making an order
    public function canBePurchased(int $requestedQuantity = 1): bool
    {
        if ($this->bundle_level_stock) {
            if (!$this->shouldTrackQuantity()) {
                return $this->hasAvailableStockStatus();
            }

            if ($this->getAllowBackorders()) {
                return true;
            }

            return $this->getCurrentQuantity() >= $requestedQuantity;
        }
        // When not using bundle-level stock, check if all items can be purchased
        return $this->items->every(function ($item) use ($requestedQuantity) {
            return $item->product->canBePurchased(
                $item->quantity * $requestedQuantity
            );
        });
    }

    //Use when Showing on the front end

    public function isInStock(): bool
    {
        if ($this->bundle_level_stock) {
            if (!$this->shouldTrackQuantity()) {
                return $this->hasAvailableStockStatus();
            }
            return $this->getCurrentQuantity() > 0 ||
                $this->getAllowBackorders();
        }

        // When not using bundle-level stock, check all items
        return $this->items->every(function ($item) {
            return $item->product->isInStock();
        });
    }
}
