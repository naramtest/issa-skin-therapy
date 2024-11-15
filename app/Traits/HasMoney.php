<?php

namespace App\Traits;

use Money\Currency;
use Money\Money;

trait HasMoney
{
    public function getMoneyRegularPriceAttribute(): Money
    {
        return new Money($this->regular_price, new Currency("USD"));
    }

    public function getMoneySalePriceAttribute(): ?Money
    {
        if ($this->sale_price === null) {
            return null;
        }
        return new Money($this->sale_price, new Currency("USD"));
    }

    public function getCurrentMoneyPriceAttribute(): Money
    {
        //isOnSale comes from HasPrice Traits
        return $this->isOnSale()
            ? $this->money_sale_price
            : $this->money_regular_price;
    }
}
