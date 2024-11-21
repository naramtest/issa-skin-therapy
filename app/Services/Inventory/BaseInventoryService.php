<?php

namespace App\Services\Inventory;

use App\Enums\QuantityAction;
use App\Enums\StockStatus;
use Illuminate\Database\Eloquent\Builder;

class BaseInventoryService
{
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

    public function getVolume(
        ?float $length,
        ?float $width,
        ?float $height
    ): ?float {
        if ($length && $width && $height) {
            return $length * $width * $height;
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
        int $quantity,
        int $currentQuantity
    ): string {
        return sprintf(
            "%s %s %d units. New quantity: %d",
            $action->getDescription(),
            $action->getSign(),
            $quantity,
            $currentQuantity
        );
    }

    protected function hasAvailableStockStatus(string $stockStatus): bool
    {
        return in_array($stockStatus, [
            StockStatus::IN_STOCK,
            StockStatus::LOW_STOCK,
            StockStatus::BACKORDER,
            StockStatus::PREORDER,
        ]);
    }

    protected function generateSKU(string $modelClass): string
    {
        do {
            $sku = strtoupper(substr(uniqid(), -6));
        } while ($modelClass::where("sku", $sku)->exists());

        return $sku;
    }
}
