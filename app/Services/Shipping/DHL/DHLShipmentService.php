<?php

namespace App\Services\Shipping\DHL;

use App\Models\Order;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DHLShipmentService
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
    public function createShipment(Order $order): array
    {
        try {
            $request = [
                "plannedShippingDateAndTime" =>
                    now()->format("Y-m-d\TH:i:s") . " GMT" . now()->format("P"),
                "pickup" => [
                    "isRequested" => false,
                ],
                "productCode" => $this->getProductCode($order),

                "getRateEstimates" => false,
                "accounts" => [
                    [
                        "typeCode" => "shipper",
                        "number" => $this->accountNumber,
                    ],
                ],
                "customerDetails" => $this->getCustomerDetails($order),
                "content" => [
                    "packages" => [
                        [
                            ...$this->weightAndDimensions($order),
                            "customerReferences" => [
                                [
                                    "value" => $order->order_number,
                                ],
                            ],
                        ],
                    ],
                    "isCustomsDeclarable" =>
                        $order->shippingAddress->country !=
                        config("store.address.country"),
                    "declaredValue" => floatval($order->total),
                    "declaredValueCurrency" => $order->currency_code,
                    "description" => "Order #" . $order->order_number,
                    "unitOfMeasurement" => config("store.unitOfMeasurement"),
                    "exportDeclaration" => [
                        "lineItems" => $this->getExportLineItems($order),
                        "invoice" => [
                            "number" => $order->order_number,
                            "date" => now()->format("Y-m-d"),
                            "customerReferences" => [
                                [
                                    "typeCode" => "CU",
                                    "value" => $order->order_number,
                                ],
                            ],
                        ],
                        "exportReason" => "PERMANENT",
                        "shipmentType" => "commercial", // P for Permanent
                    ],
                ],
                "outputImageProperties" => [
                    "printerDPI" => 300,
                    "imageOptions" => [
                        [
                            "typeCode" => "label",
                            "templateName" => "ECOM26_84_001",
                            "isRequested" => true,
                        ],
                    ],
                ],
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
                "Message-Reference-Date" => Carbon::now()->format(
                    "Y-m-d\TH:i:s\Z"
                ),
            ])->post($this->baseUrl . "shipments", $request);

            if (!$response->successful()) {
                throw new Exception(
                    "Failed to create DHL shipment: " .
                        ($response->json()["detail"] ?? "Unknown error")
                );
            }

            $shipmentData = $response->json();

            return [
                "tracking_number" => $shipmentData["shipmentTrackingNumber"],
                "label_url" => $shipmentData["documents"][0]["url"] ?? null,
                "shipping_label_data" =>
                    $shipmentData["documents"][0]["content"] ?? null,
                "raw_response" => $shipmentData,
            ];
        } catch (Exception $e) {
            Log::error("DHL Shipment Creation Error", [
                "order_id" => $order->id,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    protected function getProductCode(Order $order): ?string
    {
        // You can implement logic to determine the appropriate product code
        // based on the shipping method selected during checkout
        return $order->shipping_method->value; // P = EXPRESS WORLDWIDE
    }

    protected function getCustomerDetails(Order $order): array
    {
        $storeAddress = config("store.address");

        // Ensure addresses are no longer than 45 characters
        $shipperAddress1 = substr($storeAddress["address"], 0, 45);
        $receiverAddress1 = substr($order->shippingAddress->address, 0, 45);

        return [
            "shipperDetails" => [
                "postalAddress" => [
                    "postalCode" => $storeAddress["postal_code"],
                    "cityName" => $storeAddress["city"],
                    "countryCode" => $storeAddress["country"],
                    "addressLine1" => $shipperAddress1,
                    "addressLine2" => $storeAddress["building"] ?? "Building 1",
                    "addressLine3" => $storeAddress["flat"] ?? "Unit 1",
                ],
                "contactInformation" => [
                    "email" => $storeAddress["email"],
                    "phone" => $storeAddress["phone"],
                    "companyName" => config("store.address.name"),
                    "fullName" => config("store.address.name"),
                ],
            ],
            "receiverDetails" => [
                "postalAddress" => [
                    "postalCode" => $order->shippingAddress->postal_code,
                    "cityName" => $order->shippingAddress->city,
                    "countryCode" => $order->shippingAddress->country,
                    "addressLine1" => $receiverAddress1,
                    "addressLine2" =>
                        $order->shippingAddress->building ?? "Building 1",
                    "addressLine3" => $order->shippingAddress->flat ?? "Unit 1",
                ],
                "contactInformation" => [
                    "email" => $order->email,
                    "phone" => $order->shippingAddress->phone,
                    "companyName" => "Personal",
                    "fullName" => $order->shippingAddress->full_name,
                ],
            ],
        ];
    }

    public function weightAndDimensions(Order $order): array
    {
        $calculatePackageDimensions = $this->calculatePackageDimensions(
            $order->items
        );
        return [
            "weight" => floatval($calculatePackageDimensions["weight"]),
            "dimensions" => [
                "length" => floatval($calculatePackageDimensions["length"]),
                "width" => floatval($calculatePackageDimensions["width"]),
                "height" => floatval($calculatePackageDimensions["height"]),
            ],
        ];
    }

    protected function calculatePackageDimensions($items): array
    {
        // Basic implementation - you might want to improve this based on your needs
        $maxLength = 0;
        $maxWidth = 0;
        $maxHeight = 0;
        $totalWeight = 0;

        foreach ($items as $item) {
            $purchasable = $item->purchasable;
            $maxLength = max($maxLength, $purchasable->length ?? 0);
            $maxWidth = max($maxWidth, $purchasable->width ?? 0);
            $maxHeight = max($maxHeight, $purchasable->height ?? 0);
            $totalWeight += ($purchasable->weight ?? 0) * $item->quantity;
        }

        return [
            "length" => max($maxLength, 1),
            "width" => max($maxWidth, 1),
            "height" => max($maxHeight, 1),
            "weight" => max($totalWeight, 0.1), // Minimum 100g
        ];
    }

    protected function getExportLineItems(Order $order): array
    {
        return $order->items
            ->map(function ($item) {
                return [
                    "number" => $item->id,
                    "description" => substr($item->purchasable->name, 0, 50),
                    "price" => floatval($item->unit_price),
                    "quantity" => [
                        "value" => $item->quantity,
                        "unitOfMeasurement" => "PCS",
                    ],
                    "commodityCodes" => [
                        [
                            "typeCode" => "outbound",
                            "value" => $item->purchasable->hs_code ?? "000000",
                        ],
                    ],
                    "manufacturerCountry" =>
                        $item->purchasable->country_of_origin ?? "AE",
                    "weight" => [
                        "netValue" => floatval($item->purchasable->weight),
                        "grossValue" => floatval(
                            $item->purchasable->weight * 1.1
                        ), // Add 10% for packaging
                    ],
                ];
            })
            ->all();
    }

    public function trackShipment(string $trackingNumber): array
    {
        try {
            $response = Http::withHeaders([
                "Authorization" =>
                    "Basic " .
                    base64_encode($this->apiKey . ":" . $this->apiSecret),
                "Content-Type" => "application/json",
                "Accept" => "application/json",
            ])->get($this->baseUrl . "tracking/shipments", [
                "trackingNumber" => $trackingNumber,
            ]);

            if (!$response->successful()) {
                Log::error("DHL Tracking Failed", [
                    "tracking_number" => $trackingNumber,
                    "response" => $response->json(),
                    "status" => $response->status(),
                ]);

                throw new Exception(
                    "Failed to track shipment: " .
                        ($response->json()["detail"] ?? "Unknown error")
                );
            }

            $data = $response->json();

            // Process and format tracking data
            $events = collect($data["shipments"][0]["events"] ?? [])
                ->map(function ($event) {
                    return [
                        "timestamp" => $event["timestamp"],
                        "location" =>
                            $event["location"]["address"]["addressLocality"] ??
                            null,
                        "description" => $event["description"],
                        "status_code" => $event["statusCode"] ?? null,
                    ];
                })
                ->sortByDesc("timestamp")
                ->values()
                ->all();

            // Check if delivered
            $delivered = collect($events)->contains(function ($event) {
                return $event["status_code"] === "delivered" ||
                    str_contains(
                        strtolower($event["description"]),
                        "delivered"
                    );
            });

            $deliveredAt = $delivered
                ? collect($events)->firstWhere(function ($event) {
                    return $event["status_code"] === "delivered" ||
                        str_contains(
                            strtolower($event["description"]),
                            "delivered"
                        );
                })["timestamp"]
                : null;

            return [
                "tracking_number" => $trackingNumber,
                "events" => $events,
                "delivered" => $delivered,
                "delivered_at" => $deliveredAt,
                "status" => $delivered ? "delivered" : "in_transit",
                "raw_response" => $data,
            ];
        } catch (Exception $e) {
            Log::error("DHL Tracking Error", [
                "tracking_number" => $trackingNumber,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
