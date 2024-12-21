<?php

namespace App\Contracts;

use Money\Money;

interface Purchasable
{
    public function getId(): int;

    public function getName(): string;

    public function getCurrentMoneyPriceAttribute(): Money;

    public function inventory(): InventoryInterface;
}
