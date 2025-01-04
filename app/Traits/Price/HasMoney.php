<?php

namespace App\Traits\Price;

use App\Services\Currency\CurrencyHelper;
use Money\Money;

trait HasMoney
{
    public function getMoneyRegularPriceAttribute(): Money
    {
        return new Money(
            $this->regular_price,
            CurrencyHelper::defaultCurrency()
        );
    }

    public function getMoneySalePriceAttribute(): ?Money
    {
        if ($this->sale_price === null) {
            return null;
        }
        return new Money($this->sale_price, CurrencyHelper::defaultCurrency());
    }

    public function getCurrentMoneyPriceAttribute(): Money
    {
        //isOnSale comes from HasPrice Traits
        return $this->isOnSale()
            ? $this->money_sale_price
            : $this->money_regular_price;
    }
}
