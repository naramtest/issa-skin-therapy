<?php

namespace App\Livewire;

use App\Enums\Checkout\OrderStatus;
use App\Livewire\Forms\CheckoutForm;
use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\Checkout\CustomerCheckoutService;
use App\Services\Checkout\OrderService;
use App\Services\Payment\StripePaymentService;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Log;

class CheckoutComponent extends Component
{
    public CheckoutForm $form;
    public bool $processing = false;
    public ?string $error = null;
    public ?string $currentOrderId = null;

    protected CartService $cartService;
    protected CustomerCheckoutService $customerCheckoutService;
    protected StripePaymentService $paymentService;
    protected OrderService $orderService;

    public function boot(
        CartService $cartService,
        CustomerCheckoutService $customerCheckoutService,
        StripePaymentService $paymentService,
        OrderService $orderService
    ): void {
        $this->cartService = $cartService;
        $this->customerCheckoutService = $customerCheckoutService;
        $this->paymentService = $paymentService;
        $this->orderService = $orderService;
    }

    public function mount(): void
    {
        if ($this->cartService->isEmpty()) {
            $this->redirect(route("cart.index"));
            return;
        }

        if (auth()->check()) {
            $this->form->setFromUser(auth()->user());
        }
    }

    #[Computed]
    public function cartItems()
    {
        return $this->cartService->getItems();
    }

    #[Computed]
    public function subtotal()
    {
        return $this->cartService->getSubtotal();
    }

    #[Computed]
    public function total()
    {
        return $this->cartService->getTotal();
    }

    public function placeOrderAndPay(): void
    {
        if ($this->processing) {
            return;
        }

        $validatedData = $this->form->validate();

        // Validate cart is not empty
        if ($this->cartService->isEmpty()) {
            $this->error = __("store.Your cart is empty");
            return;
        }

        $this->processing = true;
        $this->error = null;

        try {
            if ($this->currentOrderId) {
                $existingOrder = Order::find($this->currentOrderId);
                if (
                    $existingOrder &&
                    $this->orderService->isOrderPendingPayment($existingOrder)
                ) {
                    $paymentData = $this->paymentService->getPaymentIntent(
                        $existingOrder->payment_intent_id
                    );

                    $this->dispatch(
                        "payment-ready",
                        clientSecret: $paymentData["clientSecret"]
                    );
                    return;
                }
            }
            DB::beginTransaction();
            $order = $this->customerCheckoutService->processCheckout([
                "email" => $validatedData["email"],
                "billing" => [
                    "first_name" => $validatedData["billing_first_name"],
                    "last_name" => $validatedData["billing_last_name"],
                    "phone" => $validatedData["phone"],
                    "address" => $validatedData["billing_address"],
                    "city" => $validatedData["billing_city"],
                    "state" => $validatedData["billing_state"],
                    "country" => $validatedData["billing_country"],
                    "postal_code" => $validatedData["billing_postal_code"],
                    "area" => $validatedData["billing_area"],
                    "building" => $validatedData["billing_building"],
                    "flat" => $validatedData["billing_flat"],
                ],
                "shipping" => $validatedData["different_shipping_address"]
                    ? [
                        "first_name" => $validatedData["shipping_first_name"],
                        "last_name" => $validatedData["shipping_last_name"],
                        "phone" => $validatedData["phone"],
                        "address" => $validatedData["shipping_address"],
                        "city" => $validatedData["shipping_city"],
                        "state" => $validatedData["shipping_state"],
                        "country" => $validatedData["shipping_country"],
                        "postal_code" => $validatedData["shipping_postal_code"],
                        "area" => $validatedData["shipping_area"],
                        "building" => $validatedData["shipping_building"],
                        "flat" => $validatedData["shipping_flat"],
                    ]
                    : null,
                "different_shipping_address" =>
                    $validatedData["different_shipping_address"],
                "notes" => $validatedData["order_notes"],
                "payment_method" => "card",
                "create_account" => $validatedData["create_account"],
            ]);

            $this->currentOrderId = $order->id;
            // 2. Create Stripe Payment Intent
            $paymentData = $this->paymentService->createPaymentIntent($order);

            DB::commit();

            // 3. Return the client secret for the frontend to complete the payment
            $this->dispatch(
                "payment-ready",
                clientSecret: $paymentData["clientSecret"]
            );
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Order creation failed", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);

            $this->error = __(
                "store.Failed to create order. Please try again."
            );
        } finally {
            $this->processing = false;
        }
    }

    public function updatedFormBillingCountry($value): void
    {
        //TODO: Here you could load states/regions for the selected country
        // and update the shipping country if it's the same address
        if (!$this->form->different_shipping_address) {
            $this->form->shipping_country = $value;
        }
    }

    public function updatedFormDifferentShippingAddress($value): void
    {
        if (!$value) {
            // Copy billing address to shipping address
            $this->form->shipping_first_name = $this->form->billing_first_name;
            $this->form->shipping_last_name = $this->form->billing_last_name;
            $this->form->shipping_address = $this->form->billing_address;
            $this->form->shipping_city = $this->form->billing_city;
            $this->form->shipping_state = $this->form->billing_state;
            $this->form->shipping_country = $this->form->billing_country;
            $this->form->shipping_postal_code =
                $this->form->billing_postal_code;
            $this->form->shipping_area = $this->form->billing_area;
            $this->form->shipping_building = $this->form->billing_building;
            $this->form->shipping_flat = $this->form->billing_flat;
        }
    }

    public function cleanup(): void
    {
        //TODO: makes a job to cancel order and payments
        if (!$this->currentOrderId) {
            return;
        }
        $order = Order::find($this->currentOrderId);
        $pendingOrder = $this->orderService->isOrderPendingPayment($order);
        if ($order && $pendingOrder) {
            $order->update(["status" => OrderStatus::CANCELLED]);
            //TODO:  You might want to cancel the payment intent in Stripe as well
        }
    }
}
