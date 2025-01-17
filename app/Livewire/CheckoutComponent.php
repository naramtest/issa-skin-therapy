<?php

namespace App\Livewire;

use App\Livewire\Forms\CheckoutForm;
use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\Checkout\CustomerCheckoutService;
use App\Services\Checkout\OrderService;
use App\Services\Payment\StripePaymentService;
use App\Traits\WithShippingCalculation;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Log;

class CheckoutComponent extends Component
{
    use WithShippingCalculation;

    public CheckoutForm $form;
    public Collection $shippingRates;
    public ?string $selectedShippingRate = null;
    public bool $loadingRates = false;
    public bool $processing = false;
    public ?string $error = null;
    public ?string $currentOrderId = null;
    public float $shippingCost = 0;

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

        // Initialize shipping-related properties
        $this->shippingRates = collect();
        $this->loadingRates = false;
        $this->selectedShippingRate = null;

        $this->initializeWithShippingCalculation();
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

        // Validate shipping method is selected
        if (!$this->selectedShippingRate) {
            $this->error = __("store.Please select a shipping method");
            return;
        }

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

            // Get selected shipping rate
            $shippingRate = $this->shippingRates->firstWhere(
                "service_code",
                $this->selectedShippingRate
            );

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
                "shipping_method" => $shippingRate["service_code"],
                "shipping_cost" => $shippingRate["total_price"],
                "shipping_service_name" => $shippingRate["service_name"],
            ]);

            $this->currentOrderId = $order->id;

            // Create Stripe Payment Intent
            $paymentData = $this->paymentService->createPaymentIntent($order);

            DB::commit();

            // Return the client secret for the frontend to complete the payment
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
}
