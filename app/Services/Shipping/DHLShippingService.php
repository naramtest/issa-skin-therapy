<?php

namespace App\Services\Shipping;

use App\Services\Currency\CurrencyHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DHLShippingService
{
    protected string $baseUrl = "https://express.api.dhl.com/mydhlapi/test/"; // Change to prod URL in production
    protected string $apiKey;
    protected string $apiSecret;

    protected array $domesticProducts = [
        "DOMESTIC EXPRESS" => "N",
    ];

    protected array $internationalProducts = [
        "EXPRESS WORLDWIDE" => "P",
    ];

    public function __construct()
    {
        $this->apiKey = config("services.dhl.key");
        $this->apiSecret = config("services.dhl.secret");
    }

    public function getRates(
        array $package,
        array $origin,
        array $destination
    ): array {
        try {
            // Determine if this is a domestic shipment
            $isDomestic = $origin["country"] === $destination["country"];
            $productCodes = $isDomestic
                ? $this->domesticProducts
                : $this->internationalProducts;

            // Format postal codes for UAE (ensure they're 5 digits)
            $originPostalCode = $this->formatUAEPostalCode(
                $origin["postal_code"]
            );
            $destinationPostalCode = $this->formatUAEPostalCode(
                $destination["postal_code"]
            );

            $rates = [];

            foreach ($productCodes as $productName => $productCode) {
                $request = [
                    "plannedShippingDateAndTime" => now()->format(
                        "Y-m-d\TH:i:s\Z"
                    ),
                    "unitOfMeasurement" => "metric",
                    "productCode" => $productCode,
                    "isCustomsDeclarable" => !$isDomestic,

                    "packages" => [
                        [
                            "weight" => max(0.1, floatval($package["weight"])),
                            "dimensions" => [
                                "length" => max(
                                    1,
                                    floatval($package["length"])
                                ),
                                "width" => max(1, floatval($package["width"])),
                                "height" => max(
                                    1,
                                    floatval($package["height"])
                                ),
                            ],
                        ],
                    ],

                    "accounts" => [
                        [
                            "typeCode" => "shipper",
                            "number" => config("services.dhl.account_number"),
                        ],
                    ],

                    "customerDetails" => [
                        "shipperDetails" => [
                            "postalCode" => $originPostalCode,
                            "cityName" => $origin["city"],
                            "countryCode" => $origin["country"],
                            "addressLine1" => substr($origin["address"], 0, 45),
                            "addressLine2" => $origin["building"] ?? "Unit 1",
                            "addressLine3" => $origin["flat"] ?? "Floor 1",
                            "provinceCode" => "DU", // Dubai province code
                        ],
                        "receiverDetails" => [
                            "postalCode" => $destinationPostalCode,
                            "cityName" => $destination["city"],
                            "countryCode" => $destination["country"],
                            "addressLine1" => $destination["address"],
                            "addressLine2" =>
                                $destination["building"] ?? "Unit 1",
                            "addressLine3" => $destination["flat"] ?? "Floor 1",
                            "provinceCode" => "DU",
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
                    Log::warning(
                        "DHL Rate Request Failed for product $productCode",
                        [
                            "status" => $response->status(),
                            "response" => $response->json(),
                            "request" => $request,
                        ]
                    );
                }
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

    protected function formatUAEPostalCode(string $postalCode): string
    {
        // Clean up the postal code
        $cleanPostal = preg_replace("/[^0-9]/", "", $postalCode);

        // Pad with zeros if needed (UAE postal codes are 5 digits)
        return str_pad($cleanPostal, 5, "0", STR_PAD_LEFT);
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
            $days = ceil(now()->diffInDays($deliveryDate));
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
