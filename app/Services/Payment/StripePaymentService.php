<?php

namespace App\Services\Payment;

use App\Contracts\PaymentServiceInterface;
use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Models\Order;
use App\Services\Currency\Currency;
use Exception;
use Log;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Stripe;

class StripePaymentService implements PaymentServiceInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config("services.stripe.secret_key"));
    }

    /**
     * @throws ApiErrorException
     */
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
        $paymentMethod = PaymentMethod::retrieve(
            $result["data"]->payment_method
        );

        if (!$paymentMethod) {
            return [
                "success" => false,
                "message" => "Stripe payment method not found",
            ];
        }

        $order->update([
            "status" => OrderStatus::PROCESSING,
            "payment_status" => PaymentStatus::PAID,
            "payment_authorized_at" => now(),
            "payment_captured_at" => now(),
            "payment_method_details" => [
                "type" => $paymentMethod->type,
                "last4" => $paymentMethod->card->last4 ?? null,
                "brand" => $paymentMethod->card->brand ?? null,
                "exp_month" => $paymentMethod->card->exp_month ?? null,
                "exp_year" => $paymentMethod->card->exp_year ?? null,
            ],
        ]);

        return [
            "success" => true,
            "order" => $order,
        ];
    }

    public function getPaymentStatus(string $paymentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentId);

            if ($paymentIntent->status !== "succeeded") {
                return [
                    "success" => false,
                    "status" => PaymentStatus::FAILED,
                    "message" => "Payment verification failed",
                ];
            }

            return [
                "success" => true,
                "status" => PaymentStatus::PAID,
                "data" => $paymentIntent,
            ];
        } catch (ApiErrorException $e) {
            return [
                "success" => false,
                "status" => PaymentStatus::FAILED,
                "message" => "Payment verification failed",
            ];
        }
    }

    /**
     * @throws Exception
     */
    public function getPaymentIntent(string $paymentIntentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            return [
                "clientSecret" => $paymentIntent->client_secret,
                "status" => $paymentIntent->status,
                "amount" => $paymentIntent->amount,
                "currency" => $paymentIntent->currency,
                "payment_method" => $paymentIntent->payment_method,
                "payment_method_types" => $paymentIntent->payment_method_types,
            ];
        } catch (ApiErrorException $e) {
            Log::error("Failed to get payment intent", [
                "error" => $e->getMessage(),
                "payment_intent_id" => $paymentIntentId,
            ]);
            throw new Exception("Failed to get payment details");
        }
    }

    /**
     * @throws ApiErrorException
     */
    public function cancelPaymentIntent(mixed $paymentIntentId): true
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

            // Only cancel if not already canceled or succeeded
            if (!in_array($paymentIntent->status, ["canceled", "succeeded"])) {
                $paymentIntent->cancel([
                    "cancellation_reason" => "abandoned",
                ]);
            }

            return true;
        } catch (ApiErrorException $e) {
            Log::error("Failed to cancel payment intent", [
                "payment_intent_id" => $paymentIntentId,
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public function processPayment(Order $order): array
    {
        $data = $this->createPaymentIntent($order);
        if (array_key_exists("success", $data) and $data["success"] === false) {
            return $data;
        }
        $this->updateOrder($order, $data["id"]);
        return [
            "success" => true,
            "key" => $data["client_secret"],
            "data" => $data,
            "url" => null,
        ];
    }

    public function createPaymentIntent(Order $order): array
    {
        try {
            $amount = $this->calculatePaymentAmount($order);

            return PaymentIntent::create([
                "amount" => $amount,
                "currency" => strtolower($order->currency_code),
                "automatic_payment_methods" => [
                    "enabled" => true,
                    "allow_redirects" => "always",
                ],
                "metadata" => [
                    "order_id" => $order->id,
                    "order_number" => $order->order_number,
                ],
                //                "receipt_email" => $order->email,
                "shipping" => $this->formatShippingData($order),
            ])->toArray();
        } catch (ApiErrorException $e) {
            Log::error("Stripe payment intent creation failed", [
                "error" => $e->getMessage(),
                "order" => $order->id,
            ]);
            return [
                "success" => false,
                "error" => "Failed to create checkout session",
            ];
        }
    }

    public function calculatePaymentAmount(Order $order): int
    {
        $money = Currency::convertToUserCurrencyWithCache(
            $order->getMoneyTotal(),
            $order->currency_code
        );

        $amount = $money->getAmount();

        // Handle special case for currencies with 3 decimal places (like KWD)
        if ($this->hasCurrencyThreeDecimals($order->currency_code)) {
            // Ensure the amount is divisible by 10 (last decimal is 0)
            $amount = (int) floor($amount / 10) * 10;
        }

        return $amount;
    }

    private function hasCurrencyThreeDecimals(string $currencyCode): bool
    {
        // List of currencies with 3 decimal places
        $threeDecimalCurrencies = ["BHD", "JOD", "KWD", "OMR", "TND"];
        return in_array(strtoupper($currencyCode), $threeDecimalCurrencies);
    }

    protected function formatShippingData(Order $order): array
    {
        return [
            "name" =>
                $order->shippingAddress->first_name .
                " " .
                $order->shippingAddress->last_name,
            "address" => [
                "line1" => $order->shippingAddress->address,
                "city" => $order->shippingAddress->city,
                "state" => $order->shippingAddress->state,
                "postal_code" => $order->shippingAddress->postal_code,
                "country" => $order->shippingAddress->country,
            ],
            "phone" => $order->shippingAddress->phone,
        ];
    }

    public function updateOrder(Order $order, string $id): bool
    {
        return $order->update([
            "payment_intent_id" => $id,
            "payment_provider" => "stripe",
        ]);
    }
}
