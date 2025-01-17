<?php

namespace App\Services\Cart;

use App\Enums\Checkout\CartCostType;
use App\ValueObjects\AdditionalCost;
use Money\Money;

class CartCostsManager
{
    private array $costs = [];

    public function addCost(CartCostType $type, Money $amount): void
    {
        $this->costs[$type->value] = new AdditionalCost(
            type: $type->value,
            amount: $amount,
            label: $type->getLabel(),
            taxable: $type->isTaxable()
        );
    }

    public function removeCost(CartCostType $type): void
    {
        unset($this->costs[$type->value]);
    }

    public function getCosts(): array
    {
        return $this->costs;
    }

    public function getCost(CartCostType $type): ?AdditionalCost
    {
        return $this->costs[$type->value] ?? null;
    }

    public function hasCost(CartCostType $type): bool
    {
        return isset($this->costs[$type->value]);
    }

    public function clearCosts(): void
    {
        $this->costs = [];
    }
}
