<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;
use Log;

class TabbyPaymentService
{
    protected string $baseUrl;
    protected string $publicKey;
    protected string $secretKey;
    protected string $merchantCode;

    public function __construct()
    {
        $isSandbox = config("services.tabby.is_sandbox", true);
        $this->baseUrl = "https://api.tabby.ai/api/v2/";
        $this->publicKey = config("services.tabby.public_key");
        $this->secretKey = config("services.tabby.secret_key");
        $this->merchantCode = config("services.tabby.merchant_code");
    }

    public function checkAvailability(array $data): array
    {
        try {
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . $this->secretKey,
            ])->post($this->baseUrl . "checkout", [
                "payment" => $data,
                "lang" => app()->getLocale(),
                "merchant_code" => $this->merchantCode,
                "merchant_urls" => [
                    "success" => route("checkout.success"),
                    "cancel" => route("checkout.index"),
                    "failure" => route("checkout.index"),
                ],
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error("Tabby API Error", [
                "status" => $response->status(),
                "body" => $response->json(),
            ]);

            return [
                "status" => "rejected",
                "configuration" => [
                    "products" => [
                        "installments" => [
                            "rejection_reason" => "not_available",
                        ],
                    ],
                ],
            ];
        } catch (\Exception $e) {
            Log::error("Tabby Service Error", [
                "error" => $e->getMessage(),
            ]);

            return [
                "status" => "rejected",
                "configuration" => [
                    "products" => [
                        "installments" => [
                            "rejection_reason" => "not_available",
                        ],
                    ],
                ],
            ];
        }
    }

    public function createCheckoutSession(array $data): array
    {
        try {
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . $this->secretKey,
            ])->post($this->baseUrl . "checkout", [
                "payment_type" => "installments",
                "merchant_code" => $this->merchantCode,
                "merchant_urls" => [
                    "success" => route("checkout.success"),
                    "cancel" => route("checkout.index"),
                    "failure" => route("checkout.index"),
                ],
                ...$data,
            ]);

            if ($response->successful()) {
                return [
                    "success" => true,
                    "data" => $response->json(),
                ];
            }

            return [
                "success" => false,
                "error" =>
                    $response->json()["message"] ?? "Unknown error occurred",
            ];
        } catch (\Exception $e) {
            Log::error("Tabby Checkout Creation Error", [
                "error" => $e->getMessage(),
            ]);

            return [
                "success" => false,
                "error" => "Failed to create checkout session",
            ];
        }
    }
}
