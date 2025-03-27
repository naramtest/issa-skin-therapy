<?php

namespace App\Services\Cart;

use App\Services\Cart\Redis\CartCostsRedisService;
use App\Services\Currency\CurrencyHelper;
use App\ValueObjects\CartItem;
use Money\Money;

readonly class CartPriceCalculator
{
    public function __construct(
        private CartTaxCalculator $taxCalculator,
        private CartCostsRedisService $cartCostsRedisService
    ) {
    }

    public function calculateTotal(array $items): Money
    {
        // 1. Calculate subtotal
        $subtotal = $this->calculateSubtotal($items);
        $taxableAmount = $subtotal;

        $costs = $this->cartCostsRedisService->getCosts();
        // 2. Process additional costs
        foreach ($costs as $cost) {
            $taxableAmount = $cost->taxable
                ? $taxableAmount->add($cost->amount)
                : $taxableAmount;
        }

        // 3. Calculate total with tax
        $total = $subtotal;
        dd($costs, $subtotal, $taxableAmount, $total);
        foreach ($costs as $cost) {
            $total = $cost->subtract
                ? $total->subtract($cost->amount)
                : $total->add($cost->amount);
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
