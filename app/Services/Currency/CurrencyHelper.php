<?php

namespace App\Services\Currency;

use Illuminate\Contracts\Container\BindingResolutionException;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Money\Parser\IntlLocalizedDecimalParser;
use NumberFormatter;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CurrencyHelper
{
    public static function userCurrency(): Currency
    {
        if (session()->has("currency")) {
            try {
                return new Currency(
                    session()->get("currency", config("app.money_currency"))
                );
            } catch (NotFoundExceptionInterface | ContainerExceptionInterface) {
                return self::defaultCurrency();
            }
        }
        return self::defaultCurrency();
    }

    public static function defaultCurrency(): Currency
    {
        return new Currency(config("app.money_currency"));
    }

    public static function setUserCurrency(string $currency): void
    {
        if (!self::isValidCurrency($currency)) {
            throw new \InvalidArgumentException(
                "Invalid currency code: $currency"
            );
        }
        session()->put("currency", $currency);
    }

    public static function isValidCurrency(string $currency): bool
    {
        return collect(self::getAvailableCurrencies())
            ->pluck("code")
            ->contains($currency);
    }

    public static function getAvailableCurrencies(): array
    {
        return config("currency");
    }

    public static function getUserCurrency(): string
    {
        try {
            return session()->get("currency", config("app.money_currency"));
        } catch (NotFoundExceptionInterface | ContainerExceptionInterface) {
            return config("app.money_currency");
        }
    }

    /**
     * Get currency symbol by code
     */
    public static function getCurrencySymbol(string $currencyCode): string
    {
        return collect(self::getAvailableCurrencies())->firstWhere(
            "code",
            $currencyCode
        )["symbol"] ?? $currencyCode;
    }

    /**
     * @throws BindingResolutionException
     */
    public static function moneyObjectInBlade(Money $money)
    {
        //TODO : use only to number after period
        //TODO: check if it changes when the language changes
        // TODO: check if it bad for performance
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

    /**
     * Format money object to human-readable string
     */
    public static function format(Money $money, string $locale = null): string
    {
        $formatter = new NumberFormatter(
            $locale ?? app()->getLocale(),
            NumberFormatter::CURRENCY
        );
        $moneyFormatter = new IntlMoneyFormatter(
            $formatter,
            new ISOCurrencies()
        );

        return $moneyFormatter->format($money);
    }

    public static function decimalFormatter(Money $money): string
    {
        $currencies = new ISOCurrencies();

        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        return $moneyFormatter->format($money); // outputs 1.00
    }

    /**
     * Parse a localized money string into a Money object
     */
    public static function parse(
        string $amount,
        string $currency,
        string $locale = null
    ): Money {
        $formatter = new NumberFormatter(
            $locale ?? app()->getLocale(),
            NumberFormatter::CURRENCY
        );
        $parser = new IntlLocalizedDecimalParser(
            $formatter,
            new ISOCurrencies()
        );

        return $parser->parse($amount, new Currency($currency));
    }

    public static function convertToSubunits(
        float $amount,
        string $currency
    ): int {
        // Get the number of decimal places for the currency
        $currencies = new ISOCurrencies();
        $currency = new Currency($currency);
        $subunitDivisor = 10 ** $currencies->subunitFor($currency);

        // Convert to subunits (cents)
        return (int) round($amount * $subunitDivisor);
    }
}
