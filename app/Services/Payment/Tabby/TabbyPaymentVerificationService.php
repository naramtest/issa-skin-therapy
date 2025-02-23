<?php

namespace App\Services\Payment\Tabby;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Models\Order;
use App\Services\Order\OrderProcessor;
use Exception;
use Log;

class TabbyPaymentVerificationService
{
    protected string $baseUrl;
    protected string $secretKey;

    public function __construct(private readonly OrderProcessor $orderProcessor)
    {
        $this->baseUrl = "https://api.tabby.ai/api/v2/payments/";
        $this->secretKey = config("services.tabby.secret_key") ?? "";
    }

    public function processPaymentStatus(Order $order, array $paymentData): void
    {
        try {
            if (
                $paymentData["status"] === "closed" &&
                !empty($paymentData["refunds"])
            ) {
                // This is a refund event
                $this->handleRefund($order, $paymentData);
            } else {
                // Process other statuses
                match ($paymentData["status"]) {
                    "authorized" => $this->handleAuthorized(
                        $order,
                        $paymentData
                    ),
                    "closed" => $this->handleClosed($order, $paymentData),
                    "rejected" => $this->handleRejected($order, $paymentData),
                    "expired" => $this->handleExpired($order, $paymentData),
                    "canceled" => $this->handleCanceled($order, $paymentData),
                    default => $this->handleUnknownStatus(
                        $order,
                        $paymentData
                    ),
                };
            }
        } catch (Exception $e) {
            Log::error("Failed to process Tabby payment status", [
                "order_id" => $order->id,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    private function handleRefund(Order $order, array $paymentData): void
    {
        // Get the latest refund
        $lastRefund = end($paymentData["refunds"]);

        // Calculate total refunded amount
        $totalRefunded = array_reduce(
            $paymentData["refunds"],
            function ($sum, $refund) {
                return $sum + floatval($refund["amount"]);
            },
            0
        );

        // Check if this is a full or partial refund
        $originalAmount = floatval($paymentData["amount"]);
        $isFullRefund = $totalRefunded >= $originalAmount;

        $refundDetails = [
            "refund_id" => $lastRefund["id"],
            "amount" => $lastRefund["amount"],
            "total_refunded" => $totalRefunded,
            "reason" => $lastRefund["reason"],
            "created_at" => $lastRefund["created_at"],
            "is_full_refund" => $isFullRefund,
            "refund_data" => $paymentData["refunds"],
        ];

        if ($isFullRefund) {
            $order->update([
                "status" => OrderStatus::REFUNDED,
                "payment_status" => PaymentStatus::REFUNDED,
                "payment_refunded_at" => now(),
                "payment_method_details" => array_merge(
                    $order->payment_method_details ?? [],
                    ["refund_details" => $refundDetails]
                ),
            ]);
        } else {
            // Handle partial refund
            $order->update([
                "payment_method_details" => array_merge(
                    $order->payment_method_details ?? [],
                    ["partial_refund_details" => $refundDetails]
                ),
            ]);
        }

        // Process refund through order processor
        $this->orderProcessor->processRefund($order, $refundDetails);
    }

    /**
     * @throws Exception
     */
    private function handleAuthorized(Order $order, array $paymentData): void
    {
        if ($order->payment_status !== PaymentStatus::PAID) {
            $this->orderProcessor->processSuccessfulPayment($order, [], false);
        }
    }

    private function handleClosed(Order $order, array $paymentData): void
    {
        // Handle capture (close) event
        if (!empty($paymentData["captures"])) {
            $lastCapture = end($paymentData["captures"]);
            $order->update([
                "payment_captured_at" => now(),
                "payment_method_details" => array_merge(
                    $order->payment_method_details ?? [],
                    [
                        "capture_data" => [
                            "capture_id" => $lastCapture["id"],
                            "amount" => $lastCapture["amount"],
                            "created_at" => $lastCapture["created_at"],
                            "payment_data" => $paymentData,
                        ],
                        "closed_at" => $paymentData["closed_at"],
                    ]
                ),
            ]);
        }
    }

    private function handleRejected(Order $order, array $paymentData): void
    {
        $this->orderProcessor->processFailedPayment($order, [
            "payment_id" => $paymentData["id"],
            "rejected_at" => now(),
            "rejection_data" => $paymentData,
        ]);
    }

    private function handleExpired(Order $order, array $paymentData): void
    {
        $order->update([
            "status" => OrderStatus::CANCELLED,
            "payment_status" => PaymentStatus::FAILED,
            "payment_method_details" => array_merge(
                $order->payment_method_details ?? [],
                [
                    "expired_at" => now(),
                    "expiry_data" => $paymentData,
                ]
            ),
        ]);
    }

    private function handleCanceled(Order $order, array $paymentData): void
    {
        $order->update([
            "status" => OrderStatus::CANCELLED,
            "payment_status" => PaymentStatus::FAILED,
            "payment_method_details" => array_merge(
                $order->payment_method_details ?? [],
                [
                    "canceled_at" => now(),
                    "cancellation_data" => $paymentData,
                ]
            ),
        ]);
    }

    private function handleUnknownStatus(Order $order, array $paymentData): void
    {
        Log::warning("Unknown Tabby payment status received", [
            "order_id" => $order->id,
            "payment_id" => $paymentData["id"],
            "status" => $paymentData["status"],
        ]);
    }
}
