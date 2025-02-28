<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Payment\Tabby\TabbyPaymentVerificationService;
use Illuminate\Http\Request;
use Log;

class TabbyWebhookController extends Controller
{
    public function __construct(
        private readonly TabbyPaymentVerificationService $tabbyPaymentVerificationService
    ) {
    }

    public function handleWebhook(Request $request)
    {
        logger("webhook");
        try {
            // Get the payment data from the webhook
            $payload = $request->all();
            logger($payload);

            // Find the order by payment ID
            $order = Order::where("payment_intent_id", $payload["id"])->first();
            if (!$order) {
                Log::error("Order not found for payment", [
                    "payment_id" => $payload["id"],
                ]);
                return response()->json(["error" => "Order not found"], 404);
            }
            // Process the payment status update
            $this->tabbyPaymentVerificationService->processPaymentStatus(
                $order,
                $payload
            );
            return response()->json(["status" => "success"]);
        } catch (\Exception $e) {
            Log::error("Failed to process Tabby webhook", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);

            return response()->json(
                ["error" => "Webhook processing failed"],
                500
            );
        }
    }
}
