<?php

namespace App\Traits\Checkout;

use App\Models\Order;
use App\Services\Currency\CurrencyHelper;
use App\Services\Payment\TabbyPaymentService;
use App\Services\Payment\TabbyPaymentVerificationService;
use Exception;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\On;
use Log;
use Money\Money;

trait WithPayment
{
    public bool $isAvailable = false;
    public ?string $rejectionReason = null;
    protected TabbyPaymentService $tabbyPaymentService;
    protected TabbyPaymentVerificationService $tabbyPaymentVerificationService;

    public function initializeWithPayment(
        TabbyPaymentService $tabbyPaymentService,
        TabbyPaymentVerificationService $tabbyPaymentVerificationService
    ): void {
        $this->tabbyPaymentService = $tabbyPaymentService;
        $this->tabbyPaymentVerificationService = $tabbyPaymentVerificationService;
        //        $this->checkAvailability();
    }

    public function checkAvailability(): void
    {
        try {
            $response = $this->tabbyPaymentService->checkAvailability(
                $this->getTabbyCheckoutData()
            );
            if ($response["status"] === "created") {
                $this->isAvailable = true;
                $this->rejectionReason = null;
            } else {
                $this->isAvailable = false;
                $this->rejectionReason =
                    $response["configuration"]["products"]["installments"][
                        "rejection_reason"
                    ] ?? "not_available";
            }
        } catch (Exception $e) {
            Log::error("Tabby availability check failed", [
                "error" => $e->getMessage(),
            ]);
            $this->isAvailable = false;
            $this->rejectionReason = "not_available";
        }
    }

    protected function getTabbyCheckoutData(): array
    {
        return [
            "amount" => CurrencyHelper::decimalFormatter($this->total),
            "currency" => CurrencyHelper::getUserCurrency(),
            "description" => "Order #" . time(),
            "buyer" => [
                "phone" => App::isLocal()
                    ? "otp.success@tabby.ai"
                    : $this->form->phone,
                "email" => App::isLocal()
                    ? "+971500000001"
                    : $this->form->email,
                "name" =>
                    $this->form->billing_first_name .
                    " " .
                    $this->form->billing_last_name,
            ],
            "shipping_address" => [
                "city" => $this->form->different_shipping_address
                    ? $this->form->shipping_city
                    : $this->form->billing_city,
                "address" => $this->form->different_shipping_address
                    ? $this->form->shipping_address
                    : $this->form->billing_address,
                "zip" => $this->form->different_shipping_address
                    ? $this->form->shipping_postal_code
                    : $this->form->billing_postal_code,
            ],
            "order" => [
                "reference_id" => (string) time(),
                "items" => collect($this->cartItems)
                    ->map(function ($item) {
                        return [
                            "discount_amount" => "0.00", //TODO: add item discount amount
                            "title" => $item->getPurchasable()->getName(),
                            "quantity" => $item->getQuantity(),
                            "unit_price" => CurrencyHelper::decimalFormatter(
                                $item->getPrice()
                            ),
                            "reference_id" => (string) $item
                                ->getPurchasable()
                                ->getId(),
                        ];
                    })
                    ->values()
                    ->toArray(),
                "tax_amount" => "0.00",
                "shipping_amount" => CurrencyHelper::decimalFormatter(
                    new Money(
                        $this->shippingCost,
                        CurrencyHelper::userCurrency()
                    )
                ),
                "discount_amount" => $this->discount
                    ? CurrencyHelper::decimalFormatter($this->discount)
                    : "0.00",
            ],
            "buyer_history" => [
                "registered_since" => auth()->check()
                    ? auth()->user()->created_at->toIso8601String()
                    : now()->toIso8601String(),
                "loyalty_level" => 0,
                "wishlist_count" => 0,
                "is_social_networks_connected" => false,
                "is_phone_number_verified" => false,
                "is_email_verified" => auth()->check(),
            ],
        ];
    }

    public function getBillingDetails(): array
    {
        return [
            "name" =>
                $this->form->billing_first_name .
                " " .
                $this->form->billing_last_name,
            "email" => $this->form->email,
            "phone" => $this->form->phone,
            "address" => [
                "line1" => $this->form->billing_address,
                "line2" =>
                    $this->form->billing_building .
                    " " .
                    $this->form->billing_flat,
                "city" => $this->form->billing_city,
                "state" => $this->form->billing_state,
                "postal_code" => $this->form->billing_postal_code,
                "country" => $this->form->billing_country,
            ],
        ];
    }

    #[On("payment-error")]
    public function setPaymentError($error)
    {
        $this->error = $error;
    }

    public function processTabbyPayment()
    {
        try {
            $response = $this->tabbyPaymentService->createCheckoutSession(
                $this->getTabbyCheckoutData()
            );

            if ($response["success"]) {
                // Store payment intent ID for later verification
                $this->currentOrderId = $response["data"]["payment"]["id"];

                // Redirect to Tabby checkout
                return redirect(
                    $response["data"]["configuration"]["available_products"][
                        "installments"
                    ][0]["web_url"]
                );
            }

            $this->error = $response["error"];
            return;
        } catch (Exception $e) {
            Log::error("Tabby payment processing failed", [
                "error" => $e->getMessage(),
            ]);

            $this->error = __(
                "store.Failed to process payment. Please try again"
            );
        }
    }

    public function verifyTabbyPayment(string $paymentId): void
    {
        try {
            $verificationResult = $this->tabbyPaymentVerificationService->verifyPayment(
                $paymentId
            );

            if (!$verificationResult["success"]) {
                $this->error = __(
                    "store.Payment verification failed. Please try again."
                );
                return;
            }

            $order = Order::where("payment_intent_id", $paymentId)->first();
            if (!$order) {
                $this->error = __("store.Order not found");
                return;
            }

            $this->tabbyPaymentVerificationService->processPaymentStatus(
                $order,
                $verificationResult["data"]
            );

            if ($verificationResult["status"] === "AUTHORIZED") {
                // Redirect to success page
                redirect()->route("checkout.success", [
                    "payment_intent" => $paymentId,
                ]);
            } else {
                $this->error = __(
                    "store.Payment was not authorized. Please try again."
                );
            }
        } catch (\Exception $e) {
            Log::error("Tabby payment verification failed", [
                "payment_id" => $paymentId,
                "error" => $e->getMessage(),
            ]);

            $this->error = __(
                "store.Payment verification failed. Please try again."
            );
        }
    }
}
