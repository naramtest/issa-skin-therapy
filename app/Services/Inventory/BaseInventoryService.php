<?php

namespace App\Services\Inventory;

use App\Enums\QuantityAction;
use App\Enums\StockStatus;

class BaseInventoryService
{
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

    public function generateSKU(string $modelClass): string
    {
        do {
            $sku = strtoupper(substr(uniqid(), -6));
        } while ($modelClass::where("sku", $sku)->exists());

        return $sku;
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
}
