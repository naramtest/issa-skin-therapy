<?php

namespace App\Livewire;

use App\Enums\Checkout\DHLProduct;
use App\Enums\Checkout\PaymentMethod;
use App\Enums\Checkout\ShippingMethodType;
use App\Livewire\Forms\CheckoutForm;
use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\Checkout\CustomerCheckoutService;
use App\Services\Checkout\OrderService;
use App\Services\Currency\CurrencyHelper;
use App\Services\LocationService;
use App\Traits\Checkout\LocationHandler;
use App\Traits\Checkout\WithCouponHandler;
use App\Traits\Payment\WithPayment;
use App\Traits\WithShippingCalculation;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
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
    public bool $processing = false;
    public ?string $error = null;
    public ?string $currentOrderId = null;
    public float $shippingCost = 0;

    protected CartService $cartService;
    protected CustomerCheckoutService $customerCheckoutService;

    protected OrderService $orderService;
    protected LocationService $locationService;

    public function boot(
        CartService $cartService,
        CustomerCheckoutService $customerCheckoutService,
        OrderService $orderService,
        LocationService $locationService
    ): void {
        $this->cartService = $cartService;
        $this->customerCheckoutService = $customerCheckoutService;
        $this->orderService = $orderService;
        $this->locationService = $locationService;
    }

    public function mount(): void
    {
        if ($this->cartService->isEmpty()) {
            $this->redirect(route("cart.index"));
            return;
        }

        // Initialize shipping-related properties
        $this->shippingRates = collect();
        $this->selectedShippingRate = null;
        if (auth()->check()) {
            $this->form->setFromUser(auth()->user());
            $this->checkAddressCompleteness();
            $this->checkAvailability();
        }

        // Initialize location handling
        $this->setupLocationHandler();
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
        $this->dispatch("totals-updated", total: $total);
        return $total;
    }

    public function placeOrderAndPay(): void
    {
        $validatedData = $this->validateCheckout();
        if (!$validatedData) {
            return;
        }
        $this->processing = true;
        $this->error = null;
        try {
            if ($this->currentOrderId) {
                $data = $this->oldOrderExists();
                if ($data["success"] and array_key_exists("url", $data)) {
                    $this->redirect($data["url"]);
                    return;
                }
            }

            DB::beginTransaction();
            $order = $this->createAndGetOrder($validatedData);
            $this->currentOrderId = $order->id;
            $paymentData = $this->processPayment($order);
            DB::commit();

            if ($this->form->payment_method == PaymentMethod::CARD->value) {
                $this->dispatch(
                    "payment-ready",
                    clientSecret: $paymentData["data"]["key"]
                );
            } else {
                $this->redirect($paymentData["data"]["url"]);
            }
        } catch (Exception $exception) {
            DB::rollBack();
            logger($exception);
            $this->error = __("store.Failed to create order. Please try again");
        } finally {
            $this->processing = false;
        }
    }

    protected function validateCheckout(): ?array
    {
        if ($this->processing) {
            return null;
        }

        try {
            $validatedData = $this->form->validate();

            // Validate shipping method is selected
            if (!$this->selectedShippingRate) {
                $this->error = __("store.Please select a shipping method");
                return null;
            }

            // Validate cart is not empty
            if ($this->cartService->isEmpty()) {
                $this->error = __("store.Your cart is empty");
                return null;
            }

            return $validatedData;
        } catch (Exception) {
            $this->error = __("store.Please check your input and try again");
            return null;
        }
    }

    /**
     * @param array $validatedData
     * @return Order
     */
    public function createAndGetOrder(array $validatedData): Order
    {
        // Get selected shipping rate
        $shippingRate = $this->shippingRates->firstWhere(
            "service_code",
            $this->selectedShippingRate
        );

        $dhlProduct = in_array(
            $shippingRate["service_code"],
            array_column(DHLProduct::cases(), "value")
        )
            ? DHLProduct::tryFrom($shippingRate["service_code"])
            : null;

        return $this->customerCheckoutService->processCheckout($validatedData, [
            "shipping_method" => $dhlProduct
                ? ShippingMethodType::DHL_EXPRESS
                : $shippingRate["service_name"],
            "dhl_product" => $dhlProduct?->value,
            "shipping_cost" => $shippingRate["total_price"],
        ]);
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

    public function updated($property): void
    {
        if ($this->isAddressField($property)) {
            $this->checkAddressCompleteness();
        }

        if ($this->hasTypeCompleteAddress()) {
            $this->checkAvailability();
        }
    }

    public function getPixelArray(): array
    {
        $collection = collect($this->cartItems)->map(function ($item) {
            $product = $item->getPurchasable();

            return [
                "id" => $product->facebook_id,
                "content_id" => $product->facebook_id,
                "quantity" => $item->getQuantity(),
                "content_name" => $product->getName(),
                "price" => CurrencyHelper::decimalFormatter(
                    $product->current_money_price
                ),
            ];
        });

        return [
            "facebook" => $collection->select(["id", "quantity"])->values(),
            "tikTok" => $collection
                ->select(["content_id", "quantity", "content_name", "price"])
                ->values(),
        ];
    }
}
