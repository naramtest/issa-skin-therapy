<?php

namespace App\Services\Inventory;

use App\Enums\StockStatus;
use App\Models\Product;

class InventoryManager extends BaseInventoryService
{
    protected Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function isInStock(): bool
    {
        if (!$this->shouldTrackQuantity()) {
            return $this->hasAvailableStockStatus($this->product->stock_status);
        }

        return $this->getCurrentQuantity() > 0 || $this->getAllowBackorders();
    }

    public function shouldTrackQuantity(): bool
    {
        return $this->product->track_quantity;
    }

    // Delegate shared methods where needed

    public function getCurrentQuantity(): int
    {
        return $this->product->quantity;
    }

    // Override shared methods if required

    public function getAllowBackorders(): bool
    {
        return $this->product->allow_backorders;
    }

    // Custom methods specific to Product inventory logic

    public function determineStockStatus(): StockStatus
    {
        if (!$this->shouldTrackQuantity()) {
            return $this->product->stock_status;
        }

        return match (true) {
            $this->getCurrentQuantity() <= 0 && $this->getAllowBackorders()
                => StockStatus::BACKORDER,
            $this->getCurrentQuantity() <= 0 => StockStatus::OUT_OF_STOCK,
            $this->getCurrentQuantity() <= $this->product->low_stock_threshold
                => StockStatus::LOW_STOCK,
            default => StockStatus::IN_STOCK,
        };
    }
}
