<?php

namespace App\Traits;

use App\Helpers\Money\UserCurrency;
use Money\Currency;
use Money\Money;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

trait HasMoney
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getMoneyRegularPriceAttribute(): Money
    {
        return new Money(
            $this->regular_price,
            new Currency(UserCurrency::get())
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getMoneySalePriceAttribute(): ?Money
    {
        if ($this->sale_price === null) {
            return null;
        }
        return new Money($this->sale_price, new Currency(UserCurrency::get()));
    }

    public function getCurrentMoneyPriceAttribute(): Money
    {
        //isOnSale comes from HasPrice Traits
        return $this->isOnSale()
            ? $this->money_sale_price
            : $this->money_regular_price;
    }
}
