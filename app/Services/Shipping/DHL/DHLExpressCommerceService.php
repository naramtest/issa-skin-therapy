<?php

namespace App\Services\Shipping\DHL;

use App\Models\State;
use App\Services\Currency\CurrencyHelper;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DHLExpressCommerceService
{
    protected string $apiKey;
    protected string $baseUrl = "https://api.starshipit.com/api/rates/shopify";

    public function __construct()
    {
        $this->apiKey = config("services.dhl.api_key");
    }

    public function getRates(array $items, array $destination): array
    {
        try {
            $payload = [
                "rate" => [
                    "destination" => [
                        "address1" => $destination["address"] ?? "",
                        "city" => $destination["city"],
                        "postal_code" => $destination["postal_code"],
                        "province" =>
                            State::find($destination["state"])->name ?? null,

                        "country" => $destination["country"],
                    ],
                    "items" => $this->formatItems($items),
                    "currency" => CurrencyHelper::getUserCurrency(),
                ],
            ];

            $response = Http::withHeaders([
                "Content-Type" => "application/json; charset=utf-8",
            ])->post(
                $this->baseUrl . "?" . $this->buildQueryString(),
                $payload
            );

            if (!$response->successful()) {
                Log::error("DHL Express Commerce API Error", [
                    "status" => $response->status(),
                    "error" => $response->body(),
                ]);
                return [];
            }

            $responseData = $response->json();
            return $this->formatRateResponse($responseData["rates"] ?? []);
        } catch (Exception $e) {
            Log::error("DHL Express Commerce Service Error", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);
            return [];
        }
    }

    protected function formatItems(array $items): array
    {
        return array_values(
            array_map(function ($item) {
                $product = $item->getPurchasable();

                return [
                    "name" => $product->getName(),
                    "sku" => $product->sku ?? "SKU-" . $product->getId(),
                    "quantity" => $item->getQuantity(),
                    "grams" => $product->weight, // Convert to grams, minimum 100g
                    "price" => (int) CurrencyHelper::decimalFormatter(
                        $item->getPrice()
                    ),
                ];
            }, $items)
        );
    }

    protected function buildQueryString(): string
    {
        return http_build_query([
            "apiKey" => $this->apiKey,
            "integration_type" => "woocommerce",
            "version" => "3.0",
            "format" => "json",
            "source" => "DHL",
        ]);
    }

    protected function formatRateResponse(array $rates): array
    {
        return array_map(function ($rate) {
            return [
                "service_code" => $rate["service_code"],
                "service_name" => $rate["service_name"],
                "total_price" => CurrencyHelper::convertToSubunits(
                    $rate["total_price"],
                    $rate["currency"]
                ),
                "currency" => $rate["currency"],
                "estimated_days" => $this->parseDeliveryDays($rate),
                "guaranteed" => false,
            ];
        }, $rates);
    }

    protected function parseDeliveryDays(array $rate): string
    {
        if (
            isset($rate["service_name"]) &&
            preg_match(
                "/(\d+)\s+business days/",
                $rate["service_name"],
                $matches
            )
        ) {
            return __("store.business_days", ["count" => $matches[1]]);
        }

        return __("store.N/A");
    }
}
