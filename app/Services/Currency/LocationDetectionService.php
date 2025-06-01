<?php

namespace App\Services\Currency;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationDetectionService
{
    protected array $countryToCurrency = [
        // Middle East
        "AE" => "AED", // UAE
        "QA" => "QAR", // Qatar
        "BH" => "BHD", // Bahrain
        "KW" => "KWD", // Kuwait
        "OM" => "OMR", // Oman
        "SA" => "SAR", // Saudi Arabia

        // Major currencies
        "US" => "USD", // USA
        "GB" => "GBP", // UK
        "CA" => "CAD", // Canada
        "AU" => "AUD", // Australia
        "NZ" => "NZD", // New Zealand
        "CN" => "CNY", // China
        "HK" => "HKD", // Hong Kong
        "SG" => "SGD", // Singapore
        "BR" => "BRL", // Brazil
        "BD" => "BDT", // Bangladesh

        // European countries
        "FR" => "EUR", // France
        "DE" => "EUR", // Germany
        "IT" => "EUR", // Italy
        "ES" => "EUR", // Spain
        "NL" => "EUR", // Netherlands
        "BE" => "EUR", // Belgium
        "AT" => "EUR", // Austria
        "PT" => "EUR", // Portugal
        "IE" => "EUR", // Ireland
        "FI" => "EUR", // Finland
        "GR" => "EUR", // Greece
    ];

    /**
     * Detect and set user currency based on location
     */
    public function detectAndSetUserCurrency(): string
    {
        // Check if user already has a currency preference
        if (session()->has("currency")) {
            return session("currency");
        }

        // Try to detect country from IP
        $countryCode = $this->getUserCountryFromIP();
        if ($countryCode) {
            $currency = $this->getCurrencyByCountry($countryCode);

            // Verify this currency is available in our system
            $availableCurrencies = collect(config("currency"))
                ->pluck("code")
                ->toArray();

            if (in_array($currency, $availableCurrencies)) {
                session(["currency" => $currency]);
                return $currency;
            }
        }

        // Fallback to default currency
        $defaultCurrency = CurrencyHelper::getCurrencyCode();
        session(["currency" => $defaultCurrency]);

        return $defaultCurrency;
    }

    /**
     * Get user's country code from IP address
     */
    public function getUserCountryFromIP(?string $ip = null): ?string
    {
        //        $ip = $ip ?? request()->ip();
        $ip = "37.231.83.201";
        // Cache the result for 24 hours to reduce API calls
        return Cache::remember(
            "user_country_{$ip}",
            60 * 60 * 24,
            function () use ($ip) {
                try {
                    // Using ip-api.com (free service, no API key required)
                    $response = Http::timeout(5)->get(
                        "http://ip-api.com/json/{$ip}"
                    );

                    if ($response->successful()) {
                        $data = $response->json();
                        if ($data["status"] === "success") {
                            return $data["countryCode"] ?? null;
                        }
                    }

                    // Fallback to ipinfo.io (also free for basic usage)
                    $response = Http::timeout(5)->get(
                        "https://ipinfo.io/{$ip}/json"
                    );
                    if ($response->successful()) {
                        $data = $response->json();
                        return $data["country"] ?? null;
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to detect user location", [
                        "error" => $e->getMessage(),
                        "ip" => $ip,
                    ]);
                }

                return null;
            }
        );
    }

    /**
     * Get currency based on country code
     */
    public function getCurrencyByCountry(string $countryCode): string
    {
        return $this->countryToCurrency[strtoupper($countryCode)] ??
            CurrencyHelper::getCurrencyCode();
    }

    /**
     * Get user's location details
     */
    public function getUserLocationDetails(?string $ip = null): array
    {
        $ip = $ip ?? request()->ip();

        return Cache::remember(
            "user_location_details_{$ip}",
            60 * 60 * 24,
            function () use ($ip) {
                try {
                    $response = Http::timeout(5)->get(
                        "http://ip-api.com/json/{$ip}"
                    );

                    if ($response->successful()) {
                        $data = $response->json();
                        if ($data["status"] === "success") {
                            return [
                                "country" => $data["country"] ?? null,
                                "countryCode" => $data["countryCode"] ?? null,
                                "region" => $data["regionName"] ?? null,
                                "city" => $data["city"] ?? null,
                                "zip" => $data["zip"] ?? null,
                                "timezone" => $data["timezone"] ?? null,
                                "currency" => $this->getCurrencyByCountry(
                                    $data["countryCode"] ?? ""
                                ),
                            ];
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to get location details", [
                        "error" => $e->getMessage(),
                        "ip" => $ip,
                    ]);
                }

                return [
                    "country" => null,
                    "countryCode" => null,
                    "region" => null,
                    "city" => null,
                    "zip" => null,
                    "timezone" => null,
                    "currency" => config("app.currency", "USD"),
                ];
            }
        );
    }
}
