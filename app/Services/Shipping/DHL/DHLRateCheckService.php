<?php

namespace App\Services\Shipping\DHL;

use App\Enums\Checkout\DHLProduct;
use App\Helpers\DHL\DHLAddress;
use App\Helpers\DHL\DHLHelper;
use App\Services\Currency\CurrencyHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DHLRateCheckService
{
    protected const MAX_RETRY_DAYS = 5;
    protected string $baseUrl;
    protected string $apiKey;
    protected string $apiSecret;
    protected array $domesticProducts = [];
    protected array $internationalProducts = []; // Prevent infinite loop

    public function __construct()
    {
        $this->apiKey = config("services.dhl.key");
        $this->apiSecret = config("services.dhl.secret");
        $this->baseUrl = config("services.dhl.base_url");
        $this->domesticProducts[] = DHLProduct::DOMESTIC_EXPRESS->toArray();
        $this->internationalProducts[] = DHLProduct::EXPRESS_WORLDWIDE->toArray();
    }

    public function getRates(
        array $package,
        array $origin,
        array $destination,
        int $additionalDays = 0
    ): array {
        try {
            // Determine if this is a domestic shipment
            $isDomestic = $origin["country"] === $destination["country"];
            $productCodes = $isDomestic
                ? $this->domesticProducts
                : $this->internationalProducts;

            // Format postal codes for the UAE (ensure they're 5 digits)
            $destinationPostalCode = DHLAddress::formatUAEPostalCode(
                $destination["postal_code"]
            );

            $rates = [];
            $products = [];
            $plannedDate = now()->addDays($additionalDays);
            if ($plannedDate->isToday() and $plannedDate->hour > 12) {
                $plannedDate = $plannedDate->addDay();
            }
            foreach ($productCodes as $productCode) {
                $products[] = [
                    "productCode" => $productCode["productCode"],
                    "localProductCode" => $productCode["localProductCode"],
                ];
            }
            $request = [
                "plannedShippingDateAndTime" => DHLHelper::getDate(
                    $plannedDate
                ),
                "unitOfMeasurement" => "metric",
                "isCustomsDeclarable" => !$isDomestic,
                "productsAndServices" => $products,
                "packages" => [$package],

                "accounts" => [
                    [
                        "typeCode" => "shipper",
                        "number" => config("services.dhl.account_number"),
                    ],
                ],

                "customerDetails" => [
                    "shipperDetails" => DHLAddress::shipperAddress(),
                    "receiverDetails" => [
                        "postalCode" => $destinationPostalCode,
                        "cityName" => $destination["city"],
                        "countryCode" => $destination["country"],
                        "addressLine1" => $destination["address"],
                    ],
                ],
            ];

            $response = Http::withHeaders([
                "Authorization" =>
                    "Basic " .
                    base64_encode($this->apiKey . ":" . $this->apiSecret),
                "Content-Type" => "application/json",
                "Accept" => "application/json",
            ])->post($this->baseUrl . "rates", $request);
            if ($response->successful()) {
                $responseData = $response->json();

                $formattedRates = $this->formatRateResponse($responseData);
                $rates = array_merge($rates, $formattedRates);
            } else {
                $errorDetail = $response->json()["detail"] ?? "Unknown error";

                // Check if error message indicates no available products
                if (str_contains($errorDetail, "product(s) not available")) {
                    if ($additionalDays >= self::MAX_RETRY_DAYS) {
                        return [];
                    }

                    return $this->getRates(
                        $package,
                        $origin,
                        $destination,
                        $additionalDays + 1
                    );
                }

                Log::warning("DHL Rate Request Failed", [
                    "status" => $response->status(),
                    "error" => $errorDetail,
                    "date" => $plannedDate->format("Y-m-d"),
                ]);
            }

            return array_values(array_filter($rates));
        } catch (Exception $e) {
            Log::error("DHL Rate Request Error", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);
            return [];
        }
    }

    protected function formatRateResponse(array $response): array
    {
        $rates = [];

        if (!isset($response["products"]) || !is_array($response["products"])) {
            return $rates;
        }

        foreach ($response["products"] as $product) {
            if (empty($product["totalPrice"][0])) {
                continue;
            }

            $pricing = $product["totalPrice"][0];

            if (isset($pricing["price"]) && $pricing["price"] > 0) {
                $priceInSubunits = CurrencyHelper::convertToSubunits(
                    $pricing["price"],
                    $pricing["priceCurrency"]
                );
                $rates[] = [
                    "service_code" => $product["productCode"],
                    "service_name" =>
                        $product["productName"] ??
                        "DHL " . $product["productCode"],
                    "total_price" => $priceInSubunits,
                    "currency" => $pricing["priceCurrency"],
                    "estimated_days" => $this->calculateDeliveryDays($product),
                    "guaranteed" =>
                        ($product["deliveryCapabilities"]["deliveryTypeCode"] ??
                            "") ===
                        "TD",
                ];
            }
        }

        return $rates;
    }

    protected function calculateDeliveryDays(array $product): string
    {
        if (!empty($product["deliveryCapabilities"]["deliveryTime"])) {
            return $product["deliveryCapabilities"]["deliveryTime"];
        }

        if (
            !empty(
                $product["deliveryCapabilities"]["estimatedDeliveryDateAndTime"]
            )
        ) {
            $deliveryDate = Carbon::parse(
                $product["deliveryCapabilities"]["estimatedDeliveryDateAndTime"]
            );

            $days = floor(now()->diffInDays($deliveryDate));
            return trans_choice("store.business_days", $days, [
                "count" => $days,
            ]);
        }

        if (!empty($product["deliveryCapabilities"]["totalTransitDays"])) {
            return $product["deliveryCapabilities"]["totalTransitDays"] .
                " " .
                __("store.days");
        }

        return __("store.N/A");
    }
}
