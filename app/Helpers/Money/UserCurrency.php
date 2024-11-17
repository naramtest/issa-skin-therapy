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
    public static array $currencies = [
        [
            "name" => "United Arab Emirates",
            "symbol" => "AED",
            "code" => "AED",
        ],
        [
            "name" => "Qatar",
            "symbol" => "QAR",
            "code" => "QAR",
        ],
        [
            "name" => "Bahrain",
            "symbol" => "BHD",
            "code" => "BHD",
        ],
        [
            "name" => "Kuwait",
            "symbol" => "KWD",
            "code" => "KWD",
        ],
        [
            "name" => "Oman",
            "symbol" => "OMR",
            "code" => "OMR",
        ],
        [
            "name" => "Saudi Arabia",
            "symbol" => "SAR",
            "code" => "SAR",
        ],
        [
            "name" => "United States",
            "symbol" => '$',
            "code" => "USD",
        ],
        [
            "name" => "European Union",
            "symbol" => "€",
            "code" => "EUR",
        ],
        [
            "name" => "China",
            "symbol" => "¥",
            "code" => "CNY",
        ],
        [
            "name" => "Australia",
            "symbol" => 'A$',
            "code" => "AUD",
        ],
        [
            "name" => "New Zealand",
            "symbol" => 'NZ$',
            "code" => "NZD",
        ],
        [
            "name" => "United Kingdom",
            "symbol" => "£",
            "code" => "GBP",
        ],
        [
            "name" => "Canada",
            "symbol" => 'C$',
            "code" => "CAD",
        ],
        [
            "name" => "Brazil",
            "symbol" => 'R$',
            "code" => "BRL",
        ],
        [
            "name" => "Hong Kong",
            "symbol" => 'HK$',
            "code" => "HKD",
        ],
        [
            "name" => "Singapore",
            "symbol" => 'S$',
            "code" => "SGD",
        ],
        [
            "name" => "Bangladesh",
            "symbol" => "৳",
            "code" => "BDT",
        ],
    ];

    public static function set(string $currency): void
    {
        session()->put("currency", $currency);
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
