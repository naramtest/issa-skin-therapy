<?php

namespace App\Services\Inventory;

use App\Contracts\InventoryInterface;
use App\Enums\StockStatus;
use App\Models\Product;

class InventoryManager extends BaseInventoryService implements
    InventoryInterface
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

    public function getCurrentQuantity(): int
    {
        return $this->product->quantity;
    }

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

    public function canBePurchased(int $requestedQuantity): bool
    {
        if (!$this->shouldTrackQuantity()) {
            return $this->hasAvailableStockStatus();
        }

        if ($this->getAllowBackorders()) {
            return true;
        }

        return $this->getCurrentQuantity() >= $requestedQuantity;
    }

    public function getProductVolume(): ?float
    {
        return parent::getVolume(
            $this->product->length,
            $this->product->width,
            $this->product->height
        );
    }

    public function isLowStock(): bool
    {
        if (!$this->shouldTrackQuantity()) {
            return $this->product->stock_status === StockStatus::LOW_STOCK;
        }
        return $this->getCurrentQuantity() <=
            $this->product->low_stock_threshold &&
            $this->getCurrentQuantity() > 0;
    }
}
