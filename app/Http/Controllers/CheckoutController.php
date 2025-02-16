<?php

namespace App\Http\Controllers;

use App;
use App\Enums\Checkout\PaymentStatus;
use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\Coupon\CouponService;
use App\Services\Invoice\InvoiceService;
use App\Services\Payment\StripePaymentService;
use App\Services\Payment\TabbyPaymentVerificationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly StripePaymentService $paymentService,
        private readonly CouponService $couponService,
        private readonly TabbyPaymentVerificationService $tabbyPaymentVerificationService
    ) {
    }

    public function index()
    {
        if ($this->cartService->isEmpty()) {
            return redirect()
                ->route("shop.index")
                ->with("warning", "Your cart is empty");
        }

        return view("storefront.checkout.checkout");
    }

    public function success(Request $request)
    {
        try {
            if ($request->has("payment_id")) {
                $verificationResult = $this->tabbyPaymentVerificationService->verifyPayment(
                    $request->payment_id
                );

                if (!$verificationResult["success"]) {
                    return redirect()
                        ->route("checkout.index")
                        ->with(
                            "error",
                            __("store.Payment verification failed")
                        );
                }

                $order = Order::where(
                    "payment_intent_id",
                    $request->payment_id
                )->first();

                if (!$order) {
                    return redirect()
                        ->route("checkout.index")
                        ->with("error", __("store.Order not found"));
                }

                $this->tabbyPaymentVerificationService->processPaymentStatus(
                    $order,
                    $verificationResult["data"]
                );

                if ($verificationResult["status"] !== "AUTHORIZED") {
                    return redirect()
                        ->route("checkout.index")
                        ->with("error", __("store.Payment was not authorized"));
                }
            } else {
                $order = $this->getOrderFromRequest($request);
                if (!$this->canAccessOrder($order)) {
                    return redirect()
                        ->route("checkout.index")
                        ->with("error", __("store.Invalid order access"));
                }
            }

            $discount = $order->couponUsage
                ? $this->couponService->calculateDiscount(
                    $order->couponUsage->coupon,
                    $order->getMoneySubtotal()
                )
                : null;
            // Clear cart

            if (App::isProduction()) {
                $this->cartService->clear();
            }
            return view("storefront.checkout.success", [
                "order" => $order,
                "showRegistration" =>
                    !auth()->check() &&
                    $order->customer->is_registered === false,
                "discount" => $discount,
            ]);
        } catch (Exception $e) {
            Log::error("Error processing checkout success", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route("checkout.index")
                ->with(
                    "error",
                    __(
                        "store.An error occurred while processing your order. Please contact our support team"
                    )
                );
        }
    }

    /**
     * @throws Exception
     */
    protected function getOrderFromRequest(Request $request): Order
    {
        // Get payment intent from URL
        $paymentIntentId = $request->get("payment_intent");
        if (!$paymentIntentId) {
            throw new InvalidArgumentException("Invalid payment session");
        }

        // Find order and eager load relationships
        $order = Order::where("payment_intent_id", $paymentIntentId)
            ->with(["items.purchasable", "billingAddress", "shippingAddress"])
            ->firstOrFail();

        // Verify payment if not already confirmed
        if ($order->payment_status !== PaymentStatus::PAID) {
            if (!$this->paymentService->confirmPayment($paymentIntentId)) {
                throw new Exception(
                    __(
                        "store.Payment verification failed. Please contact our support team"
                    )
                );
            }
        }

        return $order->refresh();
    }

    protected function canAccessOrder(Order $order): bool
    {
        // Logged-in users can access their own orders
        if (auth()->check()) {
            return $order->customer->user_id === auth()->id();
        }

        //TODO:what you should do when using Cash on delivery or another payment provider
        return $order->payment_intent_id === request("payment_intent") &&
            $order->created_at->gt(now()->subHours(24)) &&
            $order->payment_status === PaymentStatus::PAID;
    }

    public function cancel(Request $request)
    {
        if ($request->has("payment_id")) {
            $order = Order::where(
                "payment_intent_id",
                $request->payment_id
            )->first();
            if ($order) {
                $this->tabbyPaymentVerificationService->processPaymentStatus(
                    $order,
                    ["status" => "EXPIRED"]
                );
            }
        }

        return redirect()
            ->route("checkout.index")
            ->with(
                "error",
                __("store.Payment was cancelled. Please try again")
            );
    }

    public function failure(Request $request)
    {
        if ($request->has("payment_id")) {
            $order = Order::where(
                "payment_intent_id",
                $request->payment_id
            )->first();
            if ($order) {
                $this->tabbyPaymentVerificationService->processPaymentStatus(
                    $order,
                    ["status" => "REJECTED"]
                );
            }
        }

        return redirect()
            ->route("checkout.index")
            ->with("error", __("store.Payment was declined. Please try again"));
    }

    public function downloadInvoice(Order $order)
    {
        return app(InvoiceService::class)
            ->generateInvoice($order)
            ->toPdfInvoice()
            ->download();
        try {
            if (!$this->canAccessOrder($order)) {
                abort(403, "Unauthorized access to invoice");
            }

            return $order
                ->invoices()
                ->latest()
                ->firstOrFail()
                ->toPdfInvoice()
                ->download();
        } catch (Exception $e) {
            Log::error("Error downloading invoice", [
                "order_id" => $order->id,
                "error" => $e->getMessage(),
            ]);

            return back()->with(
                "error",
                "Unable to download the invoice. Please try again later."
            );
        }
    }
}
