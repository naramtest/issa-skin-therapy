<?php

namespace App\Helpers\Money;

use Illuminate\Contracts\Container\BindingResolutionException;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use NumberFormatter;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UserCurrency
{
    public static function set(string $currency): void
    {
        session()->set("currency", $currency);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function get()
    {
        return session()->get("currency", config("app.money_currency"));
    }

    public static function moneyObjectToString(string $locale)
    {
    }

    /**
     * @throws BindingResolutionException
     */
    public static function moneyObjectInBlade(Money $money)
    {
        //TODO: check if it changes when the language changes
        return app()
            ->makeWith(IntlMoneyFormatter::class, [
                "formatter" => new NumberFormatter(
                    app()->getLocale(),
                    NumberFormatter::CURRENCY
                ),
                "currencies" => new ISOCurrencies(),
            ])
            ->format($money);
    }
}
