<?php

namespace App\Services\Store\Currency;

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
     * Convert a Money object to user's preferred currency
     */
    public function convertToUserCurrency(Money $money): Money
    {
        try {
            $userCurrency = CurrencyHelper::getUserCurrency();

            // Return original amount if already in user's currency
            if ($money->getCurrency()->getCode() === $userCurrency) {
                return $money;
            }
            return $this->converter->convert(
                $money,
                new Currency($userCurrency)
            );
        } catch (Exception $e) {
            Log::error("Currency conversion failed: " . $e->getMessage(), [
                "from_currency" => $money->getCurrency()->getCode(),
                "to_currency" => CurrencyHelper::getUserCurrency(),
                "amount" => $money->getAmount(),
            ]);

            // Return original amount if conversion fails
            return $money;
        }
    }

    /**
     * Convert amount from one currency to another
     */
    public function convert(int|string $amount, string $from, string $to): Money
    {
        try {
            $money = new Money($amount, new Currency($from));
            return $this->converter->convert($money, new Currency($to));
        } catch (Exception $e) {
            Log::error("Currency conversion failed: " . $e->getMessage());
            return new Money($amount, new Currency($from));
        }
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
            } catch (\Exception $e) {
                \Log::error(
                    "Failed to cache exchange rate for {$baseCurrency}/{$currency}: " .
                        $e->getMessage()
                );
            }
        }
    }

    public function getExchangeRate(string $from, string $to): float
    {
        try {
            $pair = $from . "/" . $to;
            $rate = $this->swap->latest($pair);
            return $rate->getValue();
        } catch (Exception $e) {
            Log::error("Failed to get exchange rate: " . $e->getMessage());

            // Return 1 as fallback (no conversion)
            return 1.0;
        }
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
        $convertedAmount = (int) round($amount * $rate);

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

    public function convertToUserCurrencyWithCache(Money $money): Money
    {
        $userCurrency = CurrencyHelper::getUserCurrency();

        // Return original amount if already in user's currency
        if ($money->getCurrency()->getCode() === $userCurrency) {
            return $money;
        }

        $rate = $this->getCachedExchangeRate(
            $money->getCurrency(),
            $userCurrency
        );
        $convertedAmount = (int) round($money->getAmount() * $rate);

        return new Money($convertedAmount, new Currency($userCurrency));
    }
}
