<?php

namespace App\Traits\Inventory;

use App\Enums\QuantityAction;
use App\Enums\StockStatus;
use Illuminate\Database\Eloquent\Builder;

trait HasBaseInventory
{
    protected static function generateSKU(): string
    {
        do {
            $sku = strtoupper(substr(uniqid(), -6));
        } while (static::where("sku", $sku)->exists());

        return $sku;
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->orWhereIn("stock_status", [
                StockStatus::IN_STOCK->value,
                StockStatus::LOW_STOCK->value,
                StockStatus::BACKORDER->value,
                StockStatus::PREORDER->value,
            ]);
        });
    }

    public function getVolume(): ?float
    {
        if ($this->length && $this->width && $this->height) {
            return $this->length * $this->width * $this->height;
        }

        return null;
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->where("stock_status", StockStatus::LOW_STOCK->value);
    }

    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->where("stock_status", StockStatus::OUT_OF_STOCK->value);
    }

    public function getStockMovementDescription(
        QuantityAction $action,
        int $quantity
    ): string {
        return sprintf(
            "%s %s %d units. New quantity: %d",
            $action->getDescription(),
            $action->getSign(),
            $quantity,
            $this->quantity
        );
    }

    abstract protected function shouldTrackQuantity(): bool;

    abstract protected function getCurrentQuantity(): int;

    abstract protected function getAllowBackorders(): bool;

    protected function hasAvailableStockStatus(): bool
    {
        return in_array($this->stock_status, [
            StockStatus::IN_STOCK,
            StockStatus::LOW_STOCK,
            StockStatus::BACKORDER,
            StockStatus::PREORDER,
        ]);
    }
}
