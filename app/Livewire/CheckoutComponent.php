<?php

namespace App\Livewire;

use App\Enums\AddressType;
use App\Enums\Checkout\DHLProduct;
use App\Enums\Checkout\ShippingMethodType;
use App\Livewire\Forms\CheckoutForm;
use App\Models\Order;
use App\Models\State;
use App\Services\Cart\CartService;
use App\Services\Checkout\CustomerCheckoutService;
use App\Services\Checkout\OrderService;
use App\Services\Coupon\CouponService;
use App\Services\Currency\CurrencyHelper;
use App\Services\LocationService;
use App\Services\Payment\StripePaymentService;
use App\Services\Payment\TabbyPaymentService;
use App\Traits\Checkout\LocationHandler;
use App\Traits\Checkout\WithCouponHandler;
use App\Traits\WithShippingCalculation;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Log;
use Money\Money;

class CheckoutComponent extends Component
{
    use WithShippingCalculation;
    use LocationHandler;
    use WithCouponHandler;

    public CheckoutForm $form;
    public Collection $shippingRates;
    public ?string $selectedShippingRate = null;
    public bool $loadingRates = false;
    public bool $processing = false;
    public ?string $error = null;
    public ?string $currentOrderId = null;
    public float $shippingCost = 0;
    public int $stripeAmount = 0;
    public string $selectedMethod = "";
    public array $availableMethods = [];
    public bool $isAvailable = false;
    public ?string $rejectionReason = null;

    protected CartService $cartService;
    protected CustomerCheckoutService $customerCheckoutService;
    protected StripePaymentService $paymentService;
    protected OrderService $orderService;
    protected LocationService $locationService;
    protected CouponService $couponService;
    protected TabbyPaymentService $tabbyPaymentService;

    public function boot(
        CartService $cartService,
        CustomerCheckoutService $customerCheckoutService,
        StripePaymentService $paymentService,
        OrderService $orderService,
        LocationService $locationService,
        CouponService $couponService,
        TabbyPaymentService $tabbyPaymentService
    ): void {
        $this->cartService = $cartService;
        $this->customerCheckoutService = $customerCheckoutService;
        $this->paymentService = $paymentService;
        $this->orderService = $orderService;
        $this->locationService = $locationService;
        $this->couponService = $couponService;
        $this->tabbyPaymentService = $tabbyPaymentService;
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
        $this->initializeWithCouponHandler();
        // Initialize shipping-related properties
        $this->shippingRates = collect();
        $this->loadingRates = false;
        $this->selectedShippingRate = null;

        $this->initializeWithShippingCalculation();
        $this->checkAvailability(); // TODO: call when updating address
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
        } catch (\Exception $e) {
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
        $total = $this->cartService->getTotal();
        $this->stripeAmount = $total->getAmount();
        return $total;
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

    public function processTabbyPayment()
    {
        try {
            $response = $this->tabbyService->createCheckoutSession(
                $this->getTabbyCheckoutData()
            );

            if ($response["success"]) {
                // Store payment intent ID for later verification
                $this->currentOrderId = $response["data"]["payment"]["id"];

                // Redirect to Tabby checkout
                return redirect(
                    $response["data"]["configuration"]["available_products"][
                        "installments"
                    ]["web_url"]
                );
            }

            $this->error = $response["error"];
            return;
        } catch (\Exception $e) {
            Log::error("Tabby payment processing failed", [
                "error" => $e->getMessage(),
            ]);

            $this->error = __(
                "store.Failed to process payment. Please try again."
            );
        }
    }
}
