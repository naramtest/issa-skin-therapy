<?php

namespace App\Services\Shipping\DHL;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestDHLShipmentService
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

    public function createTestShipment(): array
    {
        $order = Order::get()->first();
        $timestamp = str_replace(
            ["-", ":", "."],
            "",
            Carbon::now()->format("Y-m-d\TH:i:s.u")
        );
        $messageRef = "ISSA_SHIP_" . $timestamp;
        $storeAddress = config("store.address");

        $requestData = [
            "plannedShippingDateAndTime" => "2025-02-04T06:25:22 GMT+04:00",
            "pickup" => [
                "isRequested" => false,
            ],
            "productCode" => "P", // EXPRESS WORLDWIDE
            "localProductCode" => "P",
            "accounts" => [
                [
                    "typeCode" => "shipper",
                    "number" => "454198726",
                ],
            ],
            "valueAddedServices" => [
                [
                    "serviceCode" => "WY", // Paperless Trade
                ],
                [
                    "serviceCode" => "DD", // Duty Tax Paid or Duties & Taxes Unpaid
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
            "customerReferences" => [
                [
                    "value" => "Customer reference",
                    "typeCode" => "CU",
                ],
            ],
            "customerDetails" => [
                "shipperDetails" => [
                    "postalAddress" => [
                        "postalCode" => "00000",
                        "cityName" => "Dubai",
                        "countryCode" => "AE",
                        "addressLine1" =>
                            "Express Logistics Centre Meydan, Nad al Sheba",
                    ],
                    "contactInformation" => [
                        "email" => "vitadermfze@gmail.com",
                        "phone" => "+971585957616",
                        "companyName" => "Vitaderm",
                        "fullName" => "Vitaderm",
                    ],
                ],
                "receiverDetails" => [
                    "postalAddress" => [
                        "postalCode" => "34222",
                        "cityName" => "Dammam",
                        "countryCode" => "SA",
                        "addressLine1" => "King Fahd Road",
                        "addressLine2" => "Building 123",
                    ],
                    "contactInformation" => [
                        "email" => "receiver@example.com",
                        "phone" => "+966138999999",
                        "companyName" => "Receiver Company",
                        "fullName" => "Mohammed Ahmed",
                    ],
                ],
            ],
            "content" => [
                "packages" => [
                    [
                        "weight" => 2,
                        "dimensions" => [
                            "length" => 20,
                            "width" => 15,
                            "height" => 10,
                        ],
                        "customerReferences" => [
                            [
                                "value" => "TEST-REF-001",
                                "typeCode" => "CU",
                            ],
                        ],
                        "description" => "Piece content description",
                        "labelDescription" => "Bespoke label description",
                    ],
                ],
                "isCustomsDeclarable" => true,
                "declaredValue" => 302,
                "declaredValueCurrency" => "USD",
                "incoterm" => "DDP",
                "unitOfMeasurement" => "metric",
                "description" => "Order #ORD-20250131-DNPVM",
                "exportDeclaration" => [
                    "lineItems" => [
                        [
                            "number" => 1,
                            "description" => "LumiCleanse Cleanser",
                            "price" => 20,
                            "quantity" => [
                                "value" => 6,
                                "unitOfMeasurement" => "PCS",
                            ],
                            "commodityCodes" => [
                                [
                                    "typeCode" => "outbound",
                                    "value" => "33049990",
                                ],
                            ],
                            "manufacturerCountry" => "US",
                            "weight" => [
                                "netValue" => 1.152,
                                "grossValue" => 1.2,
                            ],
                        ],
                        [
                            "number" => 2,
                            "description" =>
                                "PureHydra Oil-Free Lightweight Lotion",
                            "price" => 26,
                            "quantity" => [
                                "value" => 7,
                                "unitOfMeasurement" => "PCS",
                            ],
                            "commodityCodes" => [
                                [
                                    "typeCode" => "outbound",
                                    "value" => "33049990",
                                ],
                            ],
                            "manufacturerCountry" => "US",
                            "weight" => [
                                "netValue" => 0.854,
                                "grossValue" => 0.9,
                            ],
                        ],
                    ],
                    "invoice" => [
                        "number" => "ORD-20250131-DNPVM",
                        "date" => "2025-02-04",
                        "customerReferences" => [
                            [
                                "typeCode" => "CU",
                                "value" => "ORD-20250131-DNPVM",
                            ],
                        ],
                    ],
                    "exportReason" => "PERMANENT",
                    "shipmentType" => "commercial",
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
        ];

        try {
            // Log the request
            Log::info("DHL Test Shipment Request", ["request" => $requestData]);

            $response = Http::withHeaders([
                "Authorization" =>
                    "Basic " .
                    base64_encode($this->apiKey . ":" . $this->apiSecret),
                "Content-Type" => "application/json",
                "Message-Reference" => $messageRef,
                "Message-Reference-Date" => Carbon::now()->format(
                    "Y-m-d\TH:i:s\Z"
                ),
            ])->post($this->baseUrl . "shipments", $requestData);

            // Log the response
            Log::info("DHL Test Shipment Response", [
                "status" => $response->status(),
                "body" => $response->json(),
            ]);

            return [
                "request" => $requestData,
                "response" => $response->json(),
                "status" => $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error("DHL Test Shipment Error", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
