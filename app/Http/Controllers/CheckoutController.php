<?php

namespace App\Http\Controllers;

use App;
use App\Enums\Checkout\PaymentStatus;
use App\Helpers\PixelHelper;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\Coupon\CouponService;
use App\Services\Invoice\InvoiceService;
use App\Services\Payment\StripePaymentService;
use App\Services\Payment\Tabby\TabbyPaymentService;
use App\Services\Payment\Tabby\TabbyPaymentVerificationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly StripePaymentService $paymentService,
        private readonly CouponService $couponService,
        private readonly TabbyPaymentService $tabbyPaymentService,
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
        // Get payment intent from URL
        $paymentIntentId = $request->has("payment_id")
            ? $request->get("payment_id")
            : $request->get("payment_intent");
        if (!$paymentIntentId) {
            return redirect()
                ->route("checkout.index")
                ->with(
                    "error",
                    __(
                        "store.An error occurred while processing your order. Please contact our support team"
                    )
                );
        }
        $order = Order::where("payment_intent_id", $paymentIntentId)
            ->with(["items.purchasable", "billingAddress", "shippingAddress"])
            ->firstOrFail();

        try {
            if ($order->payment_status !== PaymentStatus::PAID) {
                if ($order->payment_provider == "tabby") {
                    $result = $this->tabbyPaymentService->confirmPayment(
                        $order,
                        $paymentIntentId
                    );
                } else {
                    $result = $this->paymentService->confirmPayment(
                        $order,
                        $paymentIntentId
                    );
                }
                if (!$result["success"]) {
                    return redirect()
                        ->route("checkout.index")
                        ->with(
                            "error",
                            $result["message"] ??
                                __(
                                    "store.An error occurred while processing your order. Please contact our support team"
                                )
                        );
                }
                $order->refresh();
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
            if (App::isProduction()) {
                Mail::to($order->email)->queue(
                    new OrderConfirmationMail($order)
                );
            }
            return view("storefront.checkout.success", [
                "order" => $order,
                "showRegistration" =>
                    !auth()->check() &&
                    $order->customer->is_registered === false,
                "discount" => $discount,
                "pixelContent" => PixelHelper::pixelContentArray($order),
            ]);
        } catch (Exception $e) {
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
                    ["status" => "canceled"]
                );
            }
        }

        return redirect()
            ->route("checkout.index")
            ->with([
                "error" => __("store.Payment was cancelled. Please try again"),
                "payment_method" => "tabby",
                "rejection_reason" => "cancelled_by_user",
            ]);
    }

    /**
     * @throws Exception
     */
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
                    ["status" => "rejected"]
                );
            }
        }
        return redirect()
            ->route("checkout.index")
            ->with([
                "error" => __("store.Payment was declined. Please try again"),
                "payment_method" => "tabby",
                "rejection_reason" => __(
                    "store.Sorry, Tabby is unable to approve this purchase. Please use an alternative payment method for your order"
                ),
            ]);
    }

    public function downloadInvoice(Order $order)
    {
        return app(InvoiceService::class)
            ->generateInvoice($order)
            ->toPdfInvoice()
            ->download();
        try {
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
