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
     * @throws Exception
     */
    public function createPaymentIntent(Order $order): array
    {
        try {
            $amount = $this->calculatePaymentAmount($order);

            $paymentIntent = PaymentIntent::create([
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
            ]);

            $order->update([
                "payment_intent_id" => $paymentIntent->id,
                "payment_provider" => "stripe",
            ]);

            return [
                "clientSecret" => $paymentIntent->client_secret,
                "publicKey" => config("services.stripe.key"),
            ];
        } catch (ApiErrorException $e) {
            Log::error("Stripe payment intent creation failed", [
                "error" => $e->getMessage(),
                "order" => $order->id,
            ]);

            throw new Exception(
                "Failed to create payment: " . $e->getMessage()
            );
        }
    }

    public function calculatePaymentAmount(Order $order): int
    {
        $money = Currency::convertToUserCurrencyWithCache(
            $order->getMoneyTotal(),
            $order->currency_code
        );
        return $money->getAmount();
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

    public function confirmPayment(string $paymentIntentId): bool
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            if ($paymentIntent->status !== "succeeded") {
                Log::error("Stripe payment intent not found");
                return false;
            }

            // Store payment method details
            $order = Order::where(
                "payment_intent_id",
                $paymentIntentId
            )->first();

            $paymentMethod = PaymentMethod::retrieve(
                $paymentIntent->payment_method
            );

            if (!$order or !$paymentMethod) {
                Log::error("Stripe payment method not found");
                return false;
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
            Log::info(
                "order " .
                    $order->id .
                    " status : " .
                    $order->payment_status->value
            );

            //TODO: event(new OrderPaid($order));
            return true;
        } catch (ApiErrorException $e) {
            Log::error("Failed to confirm payment", [
                "error" => $e->getMessage(),
                "payment_intent_id" => $paymentIntentId,
            ]);
            return false;
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
}
