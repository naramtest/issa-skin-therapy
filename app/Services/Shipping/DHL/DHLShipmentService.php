<?php

namespace App\Services\Shipping\DHL;

use App\Helpers\DHL\DHLAddress;
use App\Helpers\DHL\DHLHelper;
use App\Helpers\DHL\PaperlessTradeHelper;
use App\Models\Order;
use App\Models\ShippingOrder;
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
    public function createDHLShippingOrder(Order $order): ?ShippingOrder
    {
        $shipmentData = $this->createShipment($order);

        return $order->shippingOrder()->create([
            "carrier" => "dhl",
            "service_code" => $order->dhl_product ?? $order->shipping_method,
            "tracking_number" => $shipmentData["tracking_number"],
            "label_url" => $shipmentData["label_url"],
            "shipping_label_data" => $shipmentData["shipping_label_data"],
            "carrier_response" => $shipmentData["raw_response"],
            "status" => "created",
            "shipped_at" => now(),
        ]);
    }

    /**
     * @throws ConnectionException
     * @throws Exception
     */
    public function createShipment(Order $order, int $additionalDays = 0): array
    {
        if (!$order->dhl_product) {
            throw new Exception("Order doesn't have a DHL Shipping product");
        }
        $plannedDate = now()->addDays($additionalDays);
        if ($plannedDate->isToday() and $plannedDate->hour > 12) {
            $plannedDate = $plannedDate->addDay();
        }

        try {
            $isDomestic =
                $order->shippingAddress->country !=
                config("store.address.country");
            $request = [
                "plannedShippingDateAndTime" => DHLHelper::getDate(
                    $plannedDate
                ),
                "pickup" => [
                    "isRequested" => false,
                ],
                "productCode" => $order->dhl_product->value,
                "localProductCode" => $order->dhl_product->getLocalCode(),
                "accounts" => [
                    [
                        "typeCode" => "shipper",
                        "number" => $this->accountNumber,
                    ],
                ],
                "valueAddedServices" => $this->getValueAddedServices(
                    $order,
                    !$isDomestic
                ),

                "getRateEstimates" => false,
                "customerReferences" => [
                    [
                        "value" => "Customer reference",
                        "typeCode" => "CU",
                    ],
                ],

                "customerDetails" => DHLAddress::shipmentCustomerDetails(
                    $order
                ),
                "content" => [
                    "packages" => [
                        [
                            ...DHLHelper::weightAndDimensions($order->items),
                            "customerReferences" => [
                                [
                                    "value" => $order->order_number,
                                ],
                            ],
                        ],
                    ],
                    "isCustomsDeclarable" => $isDomestic,
                    "declaredValue" => floatval($order->total),
                    "declaredValueCurrency" => $order->currency_code,
                    "description" => "Order #" . $order->order_number,
                    "incoterm" => "DDP",
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
                        "exportReasonType" => "permanent",

                        //"shipmentType" => "commercial", // P for Permanent
                    ],
                ],
                "estimatedDeliveryDate" => [
                    "isRequested" => true,
                    "typeCode" => "QDDC",
                ],
                "getAdditionalInformation" => [
                    [
                        "typeCode" => "pickupDetails",
                        "isRequested" => true,
                    ],
                ],
                "outputImageProperties" => [
                    "printerDPI" => 300,
                    "encodingFormat" => "pdf",
                    "imageOptions" => [
                        [
                            "typeCode" => "invoice",
                            "templateName" => "COMMERCIAL_INVOICE_L_10",
                            "isRequested" => true,
                            "invoiceType" => "commercial",
                            "languageCode" => "eng",
                            "languageCountryCode" => "US",
                        ],
                        [
                            "typeCode" => "waybillDoc",
                            "hideAccountNumber" => false,
                            "templateName" => "ARCH_8x4",
                            "numberOfCopies" => 1,
                            "isRequested" => true,
                        ],
                        [
                            "typeCode" => "label",
                            "templateName" => "ECOM26_84_001",
                            "renderDHLLogo" => true,
                            "fitLabelsToA4" => true,
                        ],
                    ],
                    "splitTransportAndWaybillDocLabels" => true,
                    "allDocumentsInOneImage" => false,
                    "splitDocumentsByPages" => false,
                    "splitInvoiceAndReceipt" => true,
                    "receiptAndLabelsInOneImage" => false,
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

    /**
     * @param Order $order
     * @return array[]
     */
    public function getValueAddedServices(Order $order, bool $isDomestic): array
    {
        if ($isDomestic) {
            return [];
        }
        $valueAddedServices = [
            ["serviceCode" => "DD"], // Duty Tax Paid
        ];

        // Check if the receiver's country supports Paperless Trade (WY)
        if (
            PaperlessTradeHelper::isPaperlessTradeCountry(
                $order->shippingAddress->country
            )
        ) {
            $valueAddedServices[] = ["serviceCode" => "WY"];
        }
        return $valueAddedServices;
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
                        [
                            "typeCode" => "inbound",
                            "value" => $item->purchasable->hs_code ?? "000000", // Example inbound code
                        ],
                    ],
                    "manufacturerCountry" =>
                        $item->purchasable->country_of_origin ?? "AE",
                    "weight" => [
                        "netValue" => floatval($item->purchasable->weight),
                        "grossValue" => $item->purchasable->weight * 1.1, // Add 10% for packaging
                    ],
                    "exportReasonType" => "permanent",
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
