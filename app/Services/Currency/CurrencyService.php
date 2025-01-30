<?php

namespace App\Services\Currency;

use Exception;
use Log;
use Money\Converter;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Exchange\ReversedCurrenciesExchange;
use Money\Exchange\SwapExchange;
use Money\Money;
use Swap\Swap;

class CurrencyService
{
    protected Swap $swap;
    protected Converter $converter;
    protected ISOCurrencies $currencies;
    protected string $defaultCurrency;

    public function __construct(Swap $swap)
    {
        $this->swap = $swap;
        $this->currencies = new ISOCurrencies();
        $this->defaultCurrency = config("app.currency", "USD");
        $exchange = new SwapExchange($this->swap);
        $exchange = new ReversedCurrenciesExchange($exchange);
        $this->converter = new Converter($this->currencies, $exchange);
    }

    /**
     * Cache exchange rates for commonly used currency pairs
     */
    public function cacheExchangeRates(): void
    {
        $currencies = collect(CurrencyHelper::getAvailableCurrencies())
            ->pluck("code")
            ->toArray();
        $baseCurrency = config("app.currency", "AED");

        foreach ($currencies as $currency) {
            if ($currency === $baseCurrency) {
                continue;
            }

            try {
                $rate = $this->getExchangeRate($baseCurrency, $currency);
                cache()->put(
                    "exchange_rate_{$baseCurrency}_{$currency}",
                    $rate,
                    now()->addHours(6)
                );
            } catch (Exception $e) {
                Log::error(
                    "Failed to cache exchange rate for {$baseCurrency}/{$currency}: " .
                        $e->getMessage()
                );
            }
        }
    }

    public function getExchangeRate(string $from, string $to): float
    {
        $pair = $from . "/" . $to;
        $rate = $this->swap->latest($pair);
        return $rate->getValue();
    }

    /**
     * Convert price with caching
     */
    public function convertWithCache(
        int|string $amount,
        string $from,
        string $to
    ): Money {
        if ($from === $to) {
            return new Money($amount, new Currency($from));
        }

        $rate = $this->getCachedExchangeRate($from, $to);
        // Adjust for decimal places of source and target currencies
        $fromDecimals = $this->getCurrencyDecimals($from);
        $toDecimals = $this->getCurrencyDecimals($to);

        // Convert considering decimal places
        $multiplier = pow(10, $toDecimals - $fromDecimals);
        $convertedAmount = (int) round($amount * $rate * $multiplier);

        return new Money($convertedAmount, new Currency($to));
    }

    /**
     * Get cached exchange rate or fetch fresh rate if cache is missing
     */
    public function getCachedExchangeRate(string $from, string $to): float
    {
        $cacheKey = "exchange_rate_{$from}_{$to}";

        return cache()->remember(
            $cacheKey,
            now()->addHours(6),
            fn() => $this->getExchangeRate($from, $to)
        );
    }

    public function getCurrencyDecimals(string $currencyCode): int
    {
        $currency = new Currency($currencyCode);
        return $this->currencies->subunitFor($currency);
    }

    public function convertToUserCurrencyWithCache(
        Money $money,
        ?string $userCurrency = null,
        float $rate = null
    ): Money {
        try {
            $userCurrency ??= CurrencyHelper::getUserCurrency();

            // Return original amount if already in user's currency
            if ($money->getCurrency()->getCode() === $userCurrency) {
                return $money;
            }

            $rate ??= $this->getCachedExchangeRate(
                $money->getCurrency(),
                $userCurrency
            );

            // Adjust for decimal places of source and target currencies
            $fromDecimals = $this->getCurrencyDecimals($money->getCurrency());
            $toDecimals = $this->getCurrencyDecimals($userCurrency);

            // Convert considering decimal places
            $multiplier = pow(10, $toDecimals - $fromDecimals);
            $convertedAmount = (int) round(
                $money->getAmount() * $rate * $multiplier
            );

            return new Money($convertedAmount, new Currency($userCurrency));
        } catch (Exception) {
            return $money;
        }
    }
}
