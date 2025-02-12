<?php

namespace App\Livewire;

use App\Enums\AddressType;
use App\Enums\Checkout\DHLProduct;
use App\Enums\Checkout\PaymentMethod;
use App\Enums\Checkout\ShippingMethodType;
use App\Livewire\Forms\CheckoutForm;
use App\Models\Order;
use App\Models\State;
use App\Services\Cart\CartService;
use App\Services\Checkout\CustomerCheckoutService;
use App\Services\Checkout\OrderService;
use App\Services\LocationService;
use App\Services\Payment\StripePaymentService;
use App\Traits\Checkout\LocationHandler;
use App\Traits\Checkout\WithCouponHandler;
use App\Traits\Checkout\WithPayment;
use App\Traits\WithShippingCalculation;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Log;
use Money\Money;

class CheckoutComponent extends Component
{
    use WithCouponHandler;
    use WithShippingCalculation;
    use LocationHandler;
    use WithPayment;

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
    protected LocationService $locationService;

    public function boot(
        CartService $cartService,
        CustomerCheckoutService $customerCheckoutService,
        StripePaymentService $paymentService,
        OrderService $orderService,
        LocationService $locationService
    ): void {
        $this->cartService = $cartService;
        $this->customerCheckoutService = $customerCheckoutService;
        $this->paymentService = $paymentService;
        $this->orderService = $orderService;
        $this->locationService = $locationService;
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

        // Initialize location handling
        $this->setupLocationHandler();
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

            $dhlProduct = null;
            if (
                in_array(
                    $shippingRate["service_code"],
                    array_column(DHLProduct::cases(), "value")
                )
            ) {
                $dhlProduct = DHLProduct::tryFrom(
                    $shippingRate["service_code"]
                );
            }
            //TODO: convert data to DTO like in the OrderService
            $order = $this->customerCheckoutService->processCheckout([
                "email" => $validatedData["email"],
                "billing" => $this->getAddress(
                    $validatedData,
                    AddressType::BILLING->value
                ),
                "shipping" => $validatedData["different_shipping_address"]
                    ? $this->getAddress(
                        $validatedData,
                        AddressType::SHIPPING->value
                    )
                    : null,
                "different_shipping_address" =>
                    $validatedData["different_shipping_address"],
                "notes" => $validatedData["order_notes"],
                "payment_method" => "card", //TODO: make this dynamic
                "create_account" => $validatedData["create_account"],
                "shipping_method" => $dhlProduct
                    ? ShippingMethodType::DHL_EXPRESS
                    : $shippingRate["service_name"],
                "dhl_product" => $dhlProduct?->value,
                "shipping_cost" => $shippingRate["total_price"],
                "shipping_service_name" => $shippingRate["service_name"],
            ]);

            $this->currentOrderId = $order->id;

            // Create Stripe Payment Intent
            if ($this->form->payment_method == PaymentMethod::CARD->value) {
                $paymentData = $this->paymentService->createPaymentIntent(
                    $order
                );
            }

            DB::commit();

            // Return the client secret for the frontend to complete the payment
            if ($this->form->payment_method == PaymentMethod::CARD->value) {
                $this->dispatch(
                    "payment-ready",
                    clientSecret: $paymentData["clientSecret"]
                );
            } else {
                $this->processTabbyPayment();
            }
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

    /**
     * @param array $validatedData
     * @param string $type
     * @return array
     */
    public function getAddress(array $validatedData, string $type): array
    {
        return [
            "first_name" => $validatedData[$type . "_first_name"],
            "last_name" => $validatedData[$type . "_last_name"],
            "phone" => $validatedData["phone"],
            "address" => $validatedData[$type . "_address"],
            "city" => $validatedData[$type . "_city"],
            "state" =>
                State::find($validatedData[$type . "_state"])->name ?? null,
            "country" => $validatedData[$type . "_country"],
            "postal_code" => $validatedData[$type . "_postal_code"],
            "area" => $validatedData[$type . "_area"],
            "building" => $validatedData[$type . "_building"],
            "flat" => $validatedData[$type . "_flat"],
        ];
    }

    #[Computed]
    public function discount(): ?Money
    {
        return $this->getCouponDiscountAmount();
    }

    public function getCouponCode(): ?string
    {
        return $this->form->coupon_code;
    }

    public function setCouponCode(?string $code): void
    {
        $this->form->coupon_code = $code;
    }
}
