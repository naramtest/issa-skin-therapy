<?php

namespace App\Contracts;

use App\Enums\StockStatus;

interface InventoryInterface
{
    public function isInStock(): bool;

    public function isLowStock(): bool;

    public function canBePurchased(int $requestedQuantity): bool;

    public function determineStockStatus(): StockStatus;

    public function getCurrentQuantity(): int;

    public function shouldTrackQuantity(): bool;

    public function getAllowBackorders(): bool;
}
