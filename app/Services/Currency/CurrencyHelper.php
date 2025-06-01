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
                    session()->get("currency", self::getCurrencyCode())
                );
            } catch (NotFoundExceptionInterface | ContainerExceptionInterface) {
                return self::defaultCurrency();
            }
        }
        return self::defaultCurrency();
    }

    public static function getCurrencyCode(): string
    {
        return config("app.money_currency");
    }

    public static function defaultCurrency(): Currency
    {
        return new Currency(self::getCurrencyCode());
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

    public static function getAvailableCurrencies(): array
    {
        return config("currency");
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
                    app()->getLocale() == "ar" ? "ar-MA" : app()->getLocale(),
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

    public static function faceBookCurrency($amount): string
    {
        $currency = self::defaultCurrency();
        $money = new Money($amount, $currency);
        return self::decimalFormatter($money) . " " . $currency;
    }

    public static function decimalFormatter(
        ?Money $money = null,
        int|float $value = 0
    ): string {
        if (!$money) {
            $money = new Money($value, self::defaultCurrency());
        }
        $currencies = new ISOCurrencies();

        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        return $moneyFormatter->format($money); // outputs 1.00
    }

    public static function getCurrencyDecimals(string $currencyCode): int
    {
        $currencies = new ISOCurrencies();
        $currency = new Currency($currencyCode);
        return $currencies->subunitFor($currency);
    }

    /**
     * Ensure amount meets Stripe's requirements for three-decimal currencies
     */
    public static function ensureValidStripeAmount(
        int $amount,
        string $currencyCode
    ): int {
        $threeDecimalCurrencies = ["BHD", "JOD", "KWD", "OMR", "TND"];

        if (in_array(strtoupper($currencyCode), $threeDecimalCurrencies)) {
            return (int) floor($amount / 10) * 10;
        }

        return $amount;
    }

    public static function getUserCurrency(): string
    {
        try {
            if (session()->has("currency")) {
                return session("currency");
            }

            // Try to detect currency based on location
            $locationService = app(LocationDetectionService::class);
            return $locationService->detectAndSetUserCurrency();
        } catch (\Exception $e) {
            return self::getCurrencyCode();
        }
    }

    /**
     * Set user currency (with manual selection flag)
     */
    public static function setUserCurrency(
        string $currency,
        bool $isManual = true
    ): void {
        if (!self::isValidCurrency($currency)) {
            throw new \InvalidArgumentException(
                "Invalid currency code: $currency"
            );
        }

        session(["currency" => $currency]);

        // Mark as manually selected to prevent auto-detection override
        if ($isManual) {
            session(["currency_manually_selected" => true]);
        }
    }

    public static function isValidCurrency(string $currency): bool
    {
        return collect(self::getAvailableCurrencies())
            ->pluck("code")
            ->contains($currency);
    }

    /**
     * Clear user currency preference
     */
    public static function clearUserCurrency(): void
    {
        session()->forget(["currency", "currency_manually_selected"]);
    }

    /**
     * Get user's detected location currency (without setting it)
     */
    public static function getDetectedCurrency(): string
    {
        try {
            $locationService = app(LocationDetectionService::class);
            $countryCode = $locationService->getUserCountryFromIP();

            if ($countryCode) {
                return $locationService->getCurrencyByCountry($countryCode);
            }
        } catch (\Exception $e) {
            // Log error if needed
        }

        return self::getCurrencyCode();
    }
}
