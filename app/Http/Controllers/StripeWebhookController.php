<?php

namespace App\Http\Controllers;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header("Stripe-Signature");

        try {
            // Verify the webhook signature
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                config("services.stripe.webhook_secret")
            );

            // Handle the event
            return match ($event->type) {
                "payment_intent.succeeded"
                    => $this->handlePaymentIntentSucceeded(
                    $event->data->object
                ),
                "payment_intent.payment_failed"
                    => $this->handlePaymentIntentFailed($event->data->object),
                "charge.refunded" => $this->handleChargeRefunded(
                    $event->data->object
                ),
                default => response()->json(
                    ["status" => "Unhandled event type"],
                    200
                ),
            };
        } catch (SignatureVerificationException $e) {
            return response()->json(["error" => "Invalid signature"], 400);
        } catch (Exception $e) {
            Log::error("Webhook handling failed.", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);
            return response()->json(
                ["error" => "Webhook handling failed"],
                500
            );
        }
    }

    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        $order = Order::where("payment_intent_id", $paymentIntent->id)->first();

        if (!$order) {
            Log::error("Order not found for payment intent", [
                "payment_intent_id" => $paymentIntent->id,
            ]);
            return response()->json(["error" => "Order not found"], 404);
        }

        try {
            $order->update([
                "status" => OrderStatus::PROCESSING,
                "payment_status" => PaymentStatus::PAID,
                "payment_authorized_at" => now(),
                "payment_captured_at" => now(),
                "payment_method_details" => [
                    "type" => $paymentIntent->payment_method_type,
                    "last4" =>
                        $paymentIntent->charges->data[0]->payment_method_details
                            ->card->last4 ?? null,
                    "brand" =>
                        $paymentIntent->charges->data[0]->payment_method_details
                            ->card->brand ?? null,
                    "exp_month" =>
                        $paymentIntent->charges->data[0]->payment_method_details
                            ->card->exp_month ?? null,
                    "exp_year" =>
                        $paymentIntent->charges->data[0]->payment_method_details
                            ->card->exp_year ?? null,
                ],
            ]);

            // You can dispatch events here
            // event(new OrderPaid($order));

            return response()->json([
                "status" => "Payment processed successfully",
            ]);
        } catch (Exception $e) {
            Log::error("Failed to update order after successful payment", [
                "order_id" => $order->id,
                "error" => $e->getMessage(),
            ]);
            return response()->json(
                ["error" => "Failed to process payment"],
                500
            );
        }
    }

    protected function handlePaymentIntentFailed($paymentIntent)
    {
        $order = Order::where("payment_intent_id", $paymentIntent->id)->first();

        if (!$order) {
            Log::error("Order not found for failed payment intent", [
                "payment_intent_id" => $paymentIntent->id,
            ]);
            return response()->json(["error" => "Order not found"], 404);
        }

        try {
            $order->markPaymentFailed();

            //TODO: You can dispatch events here
            // event(new OrderPaymentFailed($order));

            return response()->json(["status" => "Payment failure recorded"]);
        } catch (Exception $e) {
            Log::error("Failed to update order after payment failure", [
                "order_id" => $order->id,
                "error" => $e->getMessage(),
            ]);
            return response()->json(
                ["error" => "Failed to process payment failure"],
                500
            );
        }
    }

    protected function handleChargeRefunded($charge)
    {
        $order = Order::where(
            "payment_intent_id",
            $charge->payment_intent
        )->first();

        if (!$order) {
            Log::error("Order not found for refunded charge", [
                "payment_intent_id" => $charge->payment_intent,
            ]);
            return response()->json(["error" => "Order not found"], 404);
        }

        try {
            $order->markPaymentRefunded();

            //TODO: You can dispatch events here
            // event(new OrderRefunded($order));

            return response()->json([
                "status" => "Refund processed successfully",
            ]);
        } catch (Exception $e) {
            Log::error("Failed to update order after refund", [
                "order_id" => $order->id,
                "error" => $e->getMessage(),
            ]);
            return response()->json(
                ["error" => "Failed to process refund"],
                500
            );
        }
    }
}
