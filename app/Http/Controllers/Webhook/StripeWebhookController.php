<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Order\OrderProcessor;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\WebhookSignature;

class StripeWebhookController extends Controller
{
    public function __construct(private readonly OrderProcessor $orderProcessor)
    {
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header("Stripe-Signature");
        try {
            $this->verifyStripeWebhook($payload, $sigHeader);
            $event = json_decode($payload, true);

            return match ($event["type"]) {
                "payment_intent.succeeded"
                    => $this->handlePaymentIntentSucceeded(
                    $event["data"]["object"]
                ),
                "payment_intent.payment_failed"
                    => $this->handlePaymentIntentFailed(
                    $event["data"]["object"]
                ),
                "charge.refunded" => $this->handleChargeRefunded(
                    $event["data"]["object"]
                ),
                default => response()->json([
                    "status" => "Unhandled event type",
                ]),
            };
        } catch (SignatureVerificationException $e) {
            Log::error("Invalid Stripe webhook signature", [
                "error" => $e->getMessage(),
                "header" => $sigHeader,
            ]);
            return response()->json(["error" => "Invalid signature"], 400);
        } catch (Exception $e) {
            Log::error("Webhook handling failed", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);
            return response()->json(
                ["error" => "Webhook handling failed"],
                500
            );
        }
    }

    /**
     * @throws SignatureVerificationException
     */
    protected function verifyStripeWebhook(
        string $payload,
        ?string $sigHeader
    ): void {
        if (!$sigHeader) {
            throw new SignatureVerificationException(
                "No signature provided",
                $sigHeader,
                "No signature provided"
            );
        }

        WebhookSignature::verifyHeader(
            $payload,
            $sigHeader,
            config("services.stripe.webhook_secret"),
            300 // Tolerance in seconds
        );
    }

    protected function handlePaymentIntentSucceeded(
        array $paymentIntent
    ): JsonResponse {
        try {
            $order = $this->getOrderFromPaymentIntent($paymentIntent);
            $this->orderProcessor->processSuccessfulPayment($order, [
                "type" => $paymentIntent["payment_method_type"] ?? null,
                "last4" =>
                    $paymentIntent["charges"]["data"][0][
                        "payment_method_details"
                    ]["card"]["last4"] ?? null,
                "brand" =>
                    $paymentIntent["charges"]["data"][0][
                        "payment_method_details"
                    ]["card"]["brand"] ?? null,
                "exp_month" =>
                    $paymentIntent["charges"]["data"][0][
                        "payment_method_details"
                    ]["card"]["exp_month"] ?? null,
                "exp_year" =>
                    $paymentIntent["charges"]["data"][0][
                        "payment_method_details"
                    ]["card"]["exp_year"] ?? null,
            ]);

            return response()->json([
                "status" => "Payment processed successfully",
            ]);
        } catch (Exception $e) {
            Log::error("Failed to process successful payment", [
                "payment_intent_id" => $paymentIntent["id"],
                "error" => $e->getMessage(),
            ]);
            return response()->json(
                ["error" => "Failed to process payment"],
                500
            );
        }
    }

    /**
     * @throws Exception
     */
    protected function getOrderFromPaymentIntent(array $paymentIntent): Order
    {
        $order = Order::where(
            "payment_intent_id",
            $paymentIntent["id"]
        )->first();

        if (!$order) {
            Log::error("Order not found for payment intent", [
                "payment_intent_id" => $paymentIntent["id"],
            ]);
            throw new Exception("Order not found");
        }

        return $order;
    }

    protected function handlePaymentIntentFailed(
        array $paymentIntent
    ): JsonResponse {
        try {
            $order = $this->getOrderFromPaymentIntent($paymentIntent);
            $this->orderProcessor->processFailedPayment($order, [
                "error_code" =>
                    $paymentIntent["last_payment_error"]["code"] ?? null,
                "error_message" =>
                    $paymentIntent["last_payment_error"]["message"] ?? null,
            ]);

            return response()->json(["status" => "Payment failure recorded"]);
        } catch (Exception $e) {
            Log::error("Failed to process payment failure", [
                "payment_intent_id" => $paymentIntent["id"],
                "error" => $e->getMessage(),
            ]);
            return response()->json(
                ["error" => "Failed to process payment failure"],
                500
            );
        }
    }

    protected function handleChargeRefunded(array $charge): JsonResponse
    {
        try {
            $order = $this->getOrderFromPaymentIntent(
                $charge["payment_intent"]
            );
            $this->orderProcessor->processRefund($order, [
                "amount" => $charge["amount_refunded"],
                "reason" => $charge["refunds"]["data"][0]["reason"] ?? null,
            ]);

            return response()->json([
                "status" => "Refund processed successfully",
            ]);
        } catch (Exception $e) {
            Log::error("Failed to process refund", [
                "charge_id" => $charge["id"],
                "error" => $e->getMessage(),
            ]);
            return response()->json(
                ["error" => "Failed to process refund"],
                500
            );
        }
    }
}
