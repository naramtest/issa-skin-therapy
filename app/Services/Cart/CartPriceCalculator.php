<?php

namespace App\Services\Cart;

use App\Services\Currency\CurrencyHelper;
use App\ValueObjects\CartItem;
use Money\Money;

class CartPriceCalculator
{
    public function __construct(
        private readonly CartCostsManager $costsManager,
        private readonly CartTaxCalculator $taxCalculator
    ) {
    }

    public function calculateTotal(array $items): Money
    {
        // 1. Calculate subtotal
        $subtotal = $this->calculateSubtotal($items);
        $taxableAmount = $subtotal;

        // 2. Process additional costs
        foreach ($this->costsManager->getCosts() as $cost) {
            $taxableAmount = $cost->taxable
                ? $taxableAmount->add($cost->amount)
                : $taxableAmount;
        }

        // 3. Calculate total with tax
        $total = $subtotal;
        foreach ($this->costsManager->getCosts() as $cost) {
            $total = $total->add($cost->amount);
        }

        $tax = $this->taxCalculator->calculateTax($taxableAmount);
        if ($tax) {
            $total = $total->add($tax);
        }

        return $total;
    }

    public function calculateSubtotal(array $items): Money
    {
        $initial = new Money(0, CurrencyHelper::defaultCurrency());

        return array_reduce(
            $items,
            fn(Money $carry, CartItem $item) => $carry->add(
                $item->getSubtotal()
            ),
            $initial
        );
    }
}
