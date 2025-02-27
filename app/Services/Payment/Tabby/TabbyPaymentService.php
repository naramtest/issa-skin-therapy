<?php

namespace App\Services\Payment\Tabby;

use App\Contracts\PaymentServiceInterface;
use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Models\Order;
use App\Services\Currency\CurrencyHelper;
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

    public function captureAuthorizedPayments(): void
    {
        $orders = Order::where("payment_provider", "tabby")
            ->where("payment_status", PaymentStatus::PAID)
            ->whereNull("payment_captured_at")
            ->where("payment_authorized_at", "<=", now()->subHours(1)) // Configurable delay
            ->get();

        foreach ($orders as $order) {
            $captureResult = $this->capturePayment($order);

            if (!$captureResult["success"]) {
                Log::warning("Automated capture failed", [
                    "order_id" => $order->id,
                    "message" => $captureResult["message"],
                ]);
            }
        }
    }

    /**
     * Capture an authorized Tabby payment
     *
     * @param Order $order
     * @return array
     */
    public function capturePayment(Order $order): array
    {
        try {
            // Ensure we have a payment intent ID
            if (!$order->payment_intent_id) {
                Log::error("No payment intent ID for order", [
                    "order_id" => $order->id,
                ]);
                return [
                    "success" => false,
                    "message" => "No payment intent found",
                ];
            }

            // Make the capture request to Tabby
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . $this->secretKey,
                "Content-Type" => "application/json",
            ])->post(
                $this->baseUrl .
                    "payments/{$order->payment_intent_id}/captures",
                [
                    "amount" => CurrencyHelper::decimalFormatter(
                        $this->convertMoney($order->getMoneyTotal())
                    ),
                    "merchant_code" => $this->merchantCode,
                ]
            );

            // Check the response
            if (!$response->successful()) {
                Log::error("Tabby capture failed", [
                    "order_id" => $order->id,
                    "response" => $response->json(),
                ]);
                return [
                    "success" => false,
                    "message" => $response->json("message") ?? "Capture failed",
                ];
            }

            // Capture successful
            $captureData = $response->json();
            $order->update([
                "payment_captured_at" => now(),
                "payment_method_details" => array_merge(
                    $order->payment_method_details ?? [],
                    [
                        "capture_data" => [
                            "capture_id" => $captureData["id"] ?? null,
                            "captured_at" => now(),
                            "full_response" => $captureData,
                        ],
                    ]
                ),
            ]);

            return [
                "success" => true,
                "message" => "Payment captured successfully",
                "data" => $captureData,
            ];
        } catch (Exception $e) {
            Log::error("Tabby payment capture error", [
                "order_id" => $order->id,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);

            return [
                "success" => false,
                "message" => "Payment capture failed: " . $e->getMessage(),
            ];
        }
    }
}
