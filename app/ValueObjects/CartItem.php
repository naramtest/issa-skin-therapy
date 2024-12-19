<?php

namespace App\ValueObjects;

use App\Contracts\Purchasable;
use Money\Money;

class CartItem
{
    public function __construct(
        private readonly string $id,
        private readonly Purchasable $purchasable,
        private int $quantity,
        private readonly array $options = [],
        private readonly ?Money $fixedPrice = null
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPurchasable(): Purchasable
    {
        return $this->purchasable;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getSubtotal(): Money
    {
        return $this->getPrice()->multiply($this->quantity);
    }

    public function getPrice(): Money
    {
        return $this->fixedPrice ?? $this->purchasable->getCurrentPrice();
    }
}
