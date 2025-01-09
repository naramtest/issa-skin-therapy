<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\Payment\StripePaymentService;
use Request;

class CheckoutController extends Controller
{
    public function index()
    {
        return view("storefront.checkout.checkout");
    }

    public function success(
        Request $request,
        StripePaymentService $paymentService
    ) {
        //TODO: success
        $paymentIntentId = $request->get("payment_intent");

        if (!$paymentIntentId) {
            return redirect()
                ->route("checkout.index")
                ->with("error", "Invalid payment session.");
        }

        try {
            // Find order by payment intent
            $order = Order::where(
                "payment_intent_id",
                $paymentIntentId
            )->firstOrFail();

            // Confirm payment
            //TODO: show error alert
            if (!$paymentService->confirmPayment($paymentIntentId)) {
                return redirect()
                    ->route("checkout.index")
                    ->with(
                        "error",
                        __(
                            "store.Payment verification failed. Please contact support"
                        )
                    );
            }

            // Clear cart after successful payment
            app(CartService::class)->clear();
            return view("storefront.checkout.success", [
                "order" => $order,
            ]);
        } catch (\Exception $e) {
            //TODO: show error alert
            report($e);
            return redirect()
                ->route("checkout.index")
                ->with(
                    "error",
                    __("store.An error occurred while processing your payment")
                );
        }
    }
}
