<?php

namespace App\Services\Payment;

use App\Contracts\PaymentServiceInterface;
use App\Models\Order;
use App\Traits\Checkout\WithTabbyData;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TabbyPaymentService implements PaymentServiceInterface
{
    use WithTabbyData;

    protected string $baseUrl;
    protected string $publicKey;
    protected string $secretKey;
    protected string $merchantCode;

    public function __construct()
    {
        $this->baseUrl = "https://api.tabby.ai/api/v2/";
        $this->publicKey = config("services.tabby.public_key");
        $this->secretKey = config("services.tabby.secret_key");
        $this->merchantCode = config("services.tabby.merchant_code");
    }

    public function confirmPayment(string $paymentIntentId): bool
    {
    }

    public function getPaymentIntent(string $paymentIntentId): array
    {
    }

    public function calculatePaymentAmount(Order $order): int
    {
    }

    public function checkAvailability(array $data): array
    {
        try {
            $response = $this->makeRequest($data);

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

    /**
     * @throws ConnectionException
     */
    protected function makeRequest(array $data)
    {
        return Http::withHeaders([
            "Authorization" => "Bearer " . $this->secretKey,
        ])->post($this->baseUrl . "checkout", [
            "payment" => $data,
            "lang" => app()->getLocale(),
            "merchant_code" => $this->merchantCode,
            "merchant_urls" => [
                "success" => route("checkout.success"),
                "cancel" => route("checkout.cancel"),
                "failure" => route("checkout.failure"),
            ],
        ]);
    }

    public function updateOrder(Order $order, string $id): bool
    {
        $order->update([
            "payment_intent_id" => $id,
            "payment_provider" => "tabby",
        ]);
    }

    public function processPayment(Order $order): array
    {
        $response = $this->createPaymentIntent($order);
        if ($response["success"]) {
            $order->update([
                "payment_intent_id" => $response["key"],
                "payment_provider" => "tabby",
            ]);
        }
        return $response;
    }

    public function createPaymentIntent(Order $order): array
    {
        try {
            $data = $this->getTabbyCheckoutData($order);
            $response = $this->makeRequest($data);
            $responseData = $response->json();
            if (!$response->successful()) {
                return [
                    "success" => false,
                    "error" =>
                        $responseData["message"] ?? "Unknown error occurred",
                ];
            }
            return [
                "success" => true,
                "key" => $responseData["payment"]["id"],
                "data" => $responseData,
                "url" =>
                    $responseData["configuration"]["available_products"][
                        "installments"
                    ][0]["web_url"],
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
