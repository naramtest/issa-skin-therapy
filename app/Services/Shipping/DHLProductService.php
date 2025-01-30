<?php

namespace App\Services\Shipping;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DHLProductService
{
    protected string $baseUrl;
    protected string $apiKey;
    protected string $apiSecret;
    protected string $accountNumber;

    public function __construct()
    {
        $this->apiKey = config("services.dhl.key");
        $this->apiSecret = config("services.dhl.secret");
        $this->accountNumber = config("services.dhl.account_number");
        $this->baseUrl = config("services.dhl.base_url");
    }

    /**
     * @throws ConnectionException
     */
    public function getProducts(array $package, array $destination): array
    {
        $address = config("store.address");
        $isDomestic = $address["country"] === $destination["country"];
        try {
            $request = [
                "accountNumber" => $this->accountNumber,
                "originCountryCode" => $address["country"],
                "originCityName" => $address["city"],
                "destinationCountryCode" => $destination["country"],
                "destinationCityName" => $destination["city"],
                "weight" => max(0.1, floatval($package["weight"])),
                "length" => max(1, floatval($package["length"])),
                "width" => max(1, floatval($package["width"])),
                "height" => max(1, floatval($package["height"])),
                "plannedShippingDate" => now()->format("Y-m-d"),
                "isCustomsDeclarable" => !$isDomestic ? "true" : "false", //TODO: check dhlCommerce website
                "unitOfMeasurement" => config("store.unitOfMeasurement"),
                "nextBusinessDay" => true,
            ];

            // Generate a message reference that meets DHL's requirements (28-36 characters)
            $timestamp = str_replace(
                ["-", ":", "."],
                "",
                Carbon::now()->format("Y-m-d\TH:i:s.u")
            );
            $messageRef = "ISSA_SHIP_" . $timestamp;

            $response = Http::withHeaders([
                "Authorization" =>
                    "Basic " .
                    base64_encode($this->apiKey . ":" . $this->apiSecret),
                "Content-Type" => "application/json",
                "Accept" => "application/json",
                "Message-Reference" => $messageRef,
                "x-version" => "2.12.0", // TODO: move to env
                "Message-Reference-Date" => Carbon::now()->format(
                    "Y-m-d\TH:i:s\Z"
                ),
            ])->get($this->baseUrl . "products", $request);

            if (!$response->successful()) {
                Log::warning("error:", [
                    "status" => $response->status(),
                    "response" => $response->json(),
                    "request" => $request,
                ]);

                throw new Exception(
                    "Failed to Get DHL Product Response: " .
                        ($response->json()["detail"] ?? "Unknown error")
                );
            }

            $shipmentData = $response->json()["products"];
            $products = [];
            foreach ($shipmentData as $shipment) {
                $products[] = [
                    "productName" => $shipment["productName"],
                    "productCode" => $shipment["productCode"],
                    "localProductCode" => $shipment["localProductCode"],
                ];
            }
            return $products;
        } catch (Exception $e) {
            Log::error("DHL Product Request Error", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
