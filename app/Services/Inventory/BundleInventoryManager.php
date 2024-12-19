<?php

namespace App\Services\Inventory;

use App\Contracts\InventoryInterface;
use App\Enums\StockStatus;
use App\Models\Bundle;

class BundleInventoryManager extends BaseInventoryService implements
    InventoryInterface
{
    protected Bundle $bundle;

    public function __construct(Bundle $bundle)
    {
        $this->bundle = $bundle;
    }

    public function isInStock(): bool
    {
        if ($this->bundle->bundle_level_stock) {
            if (!$this->shouldTrackQuantity()) {
                return $this->hasAvailableStockStatus(
                    $this->bundle->stock_status
                );
            }
            return $this->getCurrentQuantity() > 0 ||
                $this->getAllowBackorders();
        }

        // Check all items in the bundle
        return $this->bundle->items->every(
            fn($item) => $item->product->inventory()->isInStock()
        );
    }

    public function shouldTrackQuantity(): bool
    {
        return $this->bundle->bundle_level_stock &&
            $this->bundle->track_quantity;
    }

    public function getCurrentQuantity(): int
    {
        return $this->bundle->bundle_level_stock
            ? $this->bundle->quantity
            : $this->calculateLowestAvailableQuantity();
    }

    public function calculateLowestAvailableQuantity(): int
    {
        if (!count($this->bundle->items)) {
            return 0;
        }

        return $this->bundle->items
            ->map(function ($item) {
                return floor($item->product->quantity / $item->quantity);
            })
            ->min();
    }

    public function getAllowBackorders(): bool
    {
        return $this->bundle->bundle_level_stock &&
            $this->bundle->allow_backorders;
    }

    public function isLowStock(): bool
    {
        if ($this->bundle->bundle_level_stock) {
            if (!$this->shouldTrackQuantity()) {
                return $this->bundle->stock_status === StockStatus::LOW_STOCK;
            }
            return $this->getCurrentQuantity() <=
                $this->bundle->low_stock_threshold &&
                $this->getCurrentQuantity() > 0;
        }

        // Check if any item in the bundle is low stock
        return $this->bundle->items->contains(
            fn($item) => $item->product->isLowStock()
        );
    }

    public function canBePurchased(int $requestedQuantity): bool
    {
        if ($this->bundle->bundle_level_stock) {
            if (!$this->shouldTrackQuantity()) {
                return $this->hasAvailableStockStatus(
                    $this->bundle->stock_status
                );
            }
            if ($this->getAllowBackorders()) {
                return true;
            }
            return $this->getCurrentQuantity() >= $requestedQuantity;
        }

        // Check if all items can be purchased
        return $this->bundle->items->every(
            fn($item) => $item->product
                ->inventory()
                ->canBePurchased($item->quantity * $requestedQuantity)
        );
    }

    public function determineStockStatus(): StockStatus
    {
        if ($this->bundle->bundle_level_stock) {
            if (!$this->shouldTrackQuantity()) {
                return $this->bundle->stock_status;
            }
            return match (true) {
                $this->getCurrentQuantity() <= 0 && $this->getAllowBackorders()
                    => StockStatus::BACKORDER,
                $this->getCurrentQuantity() <= 0 => StockStatus::OUT_OF_STOCK,
                $this->getCurrentQuantity() <=
                    $this->bundle->low_stock_threshold
                    => StockStatus::LOW_STOCK,
                default => StockStatus::IN_STOCK,
            };
        }

        // Check stock status based on the lowest available quantity among items
        $lowestAvailableQuantity = $this->calculateLowestAvailableQuantity();
        return match (true) {
            $lowestAvailableQuantity <= 0 => StockStatus::OUT_OF_STOCK,
            $lowestAvailableQuantity <= $this->bundle->low_stock_threshold
                => StockStatus::LOW_STOCK,
            default => StockStatus::IN_STOCK,
        };
    }
}
