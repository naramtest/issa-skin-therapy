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

            // Get the original currency code
            $fromCurrency = $money->getCurrency()->getCode();

            // Try direct conversion first
            try {
                return $this->converter->convert(
                    $money,
                    new Currency($userCurrency)
                );
            } catch (Exception $e) {
                Log::info(
                    "Direct conversion failed from {$fromCurrency} to {$userCurrency}, trying through USD"
                );

                // If direct conversion fails, try through USD
                if ($fromCurrency !== "USD" && $userCurrency !== "USD") {
                    // First convert to USD
                    $usdMoney = $this->converter->convert(
                        $money,
                        new Currency("USD")
                    );

                    // Then convert from USD to target currency
                    return $this->converter->convert(
                        $usdMoney,
                        new Currency($userCurrency)
                    );
                }

                throw $e;
            }
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
}
