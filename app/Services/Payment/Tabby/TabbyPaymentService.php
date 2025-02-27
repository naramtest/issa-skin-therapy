<?php

namespace App\Services\Payment\Tabby;

use App\Contracts\PaymentServiceInterface;
use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Models\Order;
use App\Traits\Payment\WithTabbyData;
use Exception;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
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
        $this->publicKey = config("services.tabby.public_key") ?? "";
        $this->secretKey = config("services.tabby.secret_key") ?? "";
        $this->merchantCode = config("services.tabby.merchant_code") ?? "";
    }

    public function confirmPayment(Order $order, string $paymentIntentId): array
    {
        $result = $this->getPaymentStatus($paymentIntentId);
        if (!$result["success"]) {
            return [
                "success" => false,
                "message" =>
                    $result["message"] ?? "Payment verification failed",
            ];
        }

        $order->update([
            "status" => OrderStatus::PROCESSING,
            "payment_status" => PaymentStatus::PAID,
            "payment_authorized_at" => now(),
        ]);

        return [
            "success" => true,
            "order" => $order,
        ];
    }

    public function getPaymentStatus(string $paymentId): array
    {
        try {
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . $this->secretKey,
            ])->get($this->baseUrl . "payments/" . $paymentId);
            if (!$response->successful()) {
                return [
                    "success" => false,
                    "status" => PaymentStatus::FAILED,
                    "message" => "Payment verification failed",
                ];
            }

            $paymentData = $response->json();
            return match ($paymentData["status"]) {
                "CLOSED", "AUTHORIZED" => [
                    "success" => true,
                    "status" => PaymentStatus::PAID,
                    "data" => $paymentData,
                ],
                default => [
                    "success" => false,
                    "status" => PaymentStatus::FAILED,
                    "data" => $paymentData,
                ],
            };
        } catch (Exception $e) {
            return [
                "success" => false,
                "status" => PaymentStatus::FAILED,
                "message" => "Payment verification failed: " . $e->getMessage(),
            ];
        }
    }

    public function checkAvailability(array $data): array
    {
        try {
            $response = $this->makeRequest($data);

            if ($response->successful()) {
                return $response->json();
            }
            return [
                "status" => "rejected",
                "rejection_reason" => "not_available",
            ];
        } catch (Exception) {
            return [
                "status" => "rejected",
                "rejection_reason" => "not_available",
            ];
        }
    }

    /**
     * @throws ConnectionException
     */
    protected function makeRequest(array $data): PromiseInterface|Response
    {
        dd($data);
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
        return $order->update([
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
        } catch (Exception $e) {
            Log::error("Tabby Checkout Creation Error", [
                "error" => $e->getMessage(),
            ]);

            return [
                "success" => false,
                "error" => "Failed to create checkout session",
            ];
        }
    }

    public function calculatePaymentAmount(Order $order): int
    {
        // TODO: Implement calculatePaymentAmount() method.
    }
}
