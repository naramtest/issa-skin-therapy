<?php

namespace App\Traits;

use App\Enums\QuantityAction;
use App\Enums\StockStatus;
use Exception;
use Illuminate\Database\Eloquent\Builder;

trait HasInventory
{
    /**
     * Generate a unique SKU
     */
    public static function generateSKU(): string
    {
        do {
            $sku = strtoupper(substr(uniqid(), -6));
        } while (static::where("sku", $sku)->exists());

        return $sku;
    }

    /**
     * Check if the product is in stock
     */
    public function isInStock(): bool
    {
        if (!$this->track_quantity) {
            return $this->hasAvailableStockStatus();
        }

        return $this->quantity > 0 || $this->allow_backorders;
    }

    /**
     * Check if the current stock status indicates availability
     */
    protected function hasAvailableStockStatus(): bool
    {
        return in_array($this->stock_status, [
            StockStatus::IN_STOCK,
            StockStatus::LOW_STOCK,
            StockStatus::BACKORDER,
            StockStatus::PREORDER,
        ]);
    }

    /**
     * Check if the product is low in stock
     */
    public function isLowStock(): bool
    {
        if (!$this->track_quantity) {
            return $this->stock_status === StockStatus::LOW_STOCK;
        }

        return $this->quantity <= $this->low_stock_threshold &&
            $this->quantity > 0;
    }

    /**
     * Update stock quantity and status
     *
     * @throws Exception
     */
    public function updateStock(int $quantity, QuantityAction $action): void
    {
        if (!$this->track_quantity) {
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

    /**
     * Determine stock status based on quantity and settings
     */
    protected function determineStockStatus(int $quantity): StockStatus
    {
        if (!$this->track_quantity) {
            return $this->stock_status;
        }

        return match (true) {
            $quantity <= 0 && $this->allow_backorders => StockStatus::BACKORDER,
            $quantity <= 0 => StockStatus::OUT_OF_STOCK,
            $quantity <= $this->low_stock_threshold => StockStatus::LOW_STOCK,
            default => StockStatus::IN_STOCK,
        };
    }

    /**
     * Check if product can be purchased
     */
    public function canBePurchased(int $requestedQuantity = 1): bool
    {
        if (!$this->track_quantity) {
            return $this->hasAvailableStockStatus();
        }

        if ($this->allow_backorders) {
            return true;
        }

        return $this->quantity >= $requestedQuantity;
    }

    /**
     * Scope for products available for purchase
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query
                ->where("track_quantity", false)
                ->orWhereIn("stock_status", [
                    StockStatus::IN_STOCK->value,
                    StockStatus::LOW_STOCK->value,
                    StockStatus::BACKORDER->value,
                    StockStatus::PREORDER->value,
                ]);
        });
    }

    // ... other methods remain the same ...
}
