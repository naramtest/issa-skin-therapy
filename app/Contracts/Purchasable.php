<?php

namespace App\Contracts;

use Money\Money;

interface Purchasable
{
    public function getId(): int;

    public function getName(): string;

    public function getCurrentPrice(): Money;

    public function inventory(): InventoryInterface;
}
