<?php

namespace App\Services\Payment;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Models\Order;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TabbyPaymentVerificationService
{
    protected string $baseUrl;
    protected string $secretKey;

    public function __construct()
    {
        $this->baseUrl = "https://api.tabby.ai/api/v2/payments/";
        $this->secretKey = config("services.tabby.secret_key");
    }

    public function verifyPayment(string $paymentId): array
    {
        try {
            $response = $this->makeRequest($paymentId);
            if (!$response->successful()) {
                return [
                    "success" => false,
                    "status" => "error",
                    "message" => "Payment verification failed",
                ];
            }

            $paymentData = $response->json();
            return [
                "success" => true,
                "status" => $paymentData["status"],
                "data" => $paymentData,
            ];
        } catch (Exception $e) {
            Log::error("Tabby payment verification error", [
                "payment_id" => $paymentId,
                "error" => $e->getMessage(),
            ]);

            return [
                "success" => false,
                "status" => "error",
                "message" => "Payment verification failed: " . $e->getMessage(),
            ];
        }
    }

    protected function makeRequest(string $paymentId): Response
    {
        return Http::withHeaders([
            "Authorization" => "Bearer " . $this->secretKey,
        ])->get($this->baseUrl . $paymentId);
    }

    public function processPaymentStatus(Order $order, array $paymentData): void
    {
        switch ($paymentData["status"]) {
            case "AUTHORIZED":
                $order->update([
                    "status" => OrderStatus::PROCESSING,
                    "payment_status" => PaymentStatus::PAID,
                    "payment_authorized_at" => now(),
                ]);
                break;

            case "EXPIRED":
            case "REJECTED":
                $order->update([
                    "status" => OrderStatus::CANCELLED,
                    "payment_status" => PaymentStatus::FAILED,
                ]);
                break;

            case "CLOSED":
                if (!$order->payment_captured_at) {
                    $order->update([
                        "payment_captured_at" => now(),
                    ]);
                }
                break;
        }
    }
}
