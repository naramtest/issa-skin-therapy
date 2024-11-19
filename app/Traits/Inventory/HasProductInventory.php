<?php

namespace App\Traits\Inventory;

use App\Enums\QuantityAction;
use App\Enums\StockStatus;
use Exception;

trait HasProductInventory
{
    use HasBaseInventory;

    //Use when Showing on the front end

    public function isInStock(): bool
    {
        if (!$this->shouldTrackQuantity()) {
            return $this->hasAvailableStockStatus();
        }

        return $this->getCurrentQuantity() > 0 || $this->getAllowBackorders();
    }

    protected function shouldTrackQuantity(): bool
    {
        return $this->track_quantity;
    }

    protected function getCurrentQuantity(): int
    {
        return $this->quantity;
    }

    protected function getAllowBackorders(): bool
    {
        return $this->allow_backorders;
    }

    //Use when Showing on the front end

    public function isLowStock(): bool
    {
        if (!$this->shouldTrackQuantity()) {
            return $this->stock_status === StockStatus::LOW_STOCK;
        }

        return $this->getCurrentQuantity() <= $this->low_stock_threshold &&
            $this->getCurrentQuantity() > 0;
    }

    //Use When Making Order
    public function canBePurchased(int $requestedQuantity = 1): bool
    {
        if (!$this->shouldTrackQuantity()) {
            return $this->hasAvailableStockStatus();
        }
        if ($this->getAllowBackorders()) {
            return true;
        }

        return $this->getCurrentQuantity() >= $requestedQuantity;
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

        if (!$this->allow_backorders) {
            $newQuantity = max(0, $newQuantity);
        }

        $newStatus = $this->determineStockStatus($newQuantity);

        $this->update([
            "quantity" => $newQuantity,
            "stock_status" => $newStatus,
        ]);
    }

    //use when Updating a Product
    protected function determineStockStatus(int $quantity): StockStatus
    {
        if (!$this->shouldTrackQuantity()) {
            return $this->stock_status;
        }

        return match (true) {
            $quantity <= 0 && $this->getAllowBackorders()
                => StockStatus::BACKORDER,
            $quantity <= 0 => StockStatus::OUT_OF_STOCK,
            $quantity <= $this->low_stock_threshold => StockStatus::LOW_STOCK,
            default => StockStatus::IN_STOCK,
        };
    }
}
