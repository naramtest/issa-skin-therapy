<?php

namespace App\Livewire;

use App\Livewire\Forms\CheckoutForm;
use App\Services\Cart\CartService;
use App\Services\Checkout\CustomerCheckoutService;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Log;

class CheckoutComponent extends Component
{
    public CheckoutForm $form;

    protected CartService $cartService;
    protected CustomerCheckoutService $customerCheckoutService;

    public function boot(
        CartService $cartService,
        CustomerCheckoutService $customerCheckoutService
    ): void {
        $this->cartService = $cartService;
        $this->customerCheckoutService = $customerCheckoutService;
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
        try {
            return $this->cartService->getItems();
        } catch (Exception) {
            return [];
        }
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

    public function placeOrder(): void
    {
        $validatedData = $this->form->validate();

        try {
            DB::beginTransaction();

            $order = $this->customerCheckoutService->processCheckout([
                "email" => $this->form->email,
                "billing" => [
                    "first_name" => $this->form->billing_first_name,
                    "last_name" => $this->form->billing_last_name,
                    "phone" => $this->form->phone,
                    "address" => $this->form->billing_address,
                    "city" => $this->form->billing_city,
                    "state" => $this->form->billing_state,
                    "country" => $this->form->billing_country,
                    "postal_code" => $this->form->billing_postal_code,
                    "area" => $this->form->billing_area,
                    "building" => $this->form->billing_building,
                    "flat" => $this->form->billing_flat,
                ],
                "shipping" => $this->form->different_shipping_address
                    ? [
                        "first_name" => $this->form->shipping_first_name,
                        "last_name" => $this->form->shipping_last_name,
                        "phone" => $this->form->phone,
                        "address" => $this->form->shipping_address,
                        "city" => $this->form->shipping_city,
                        "state" => $this->form->shipping_state,
                        "country" => $this->form->shipping_country,
                        "postal_code" => $this->form->shipping_postal_code,
                        "area" => $this->form->shipping_area,
                        "building" => $this->form->shipping_building,
                        "flat" => $this->form->shipping_flat,
                    ]
                    : null,
                "different_shipping_address" =>
                    $this->form->different_shipping_address,
                "notes" => $this->form->order_notes,
                "shipping_method" => $this->form->shipping_method ?? null,
                "payment_method" => $this->form->payment_method,
                "create_account" => $this->form->create_account,
            ]);

            DB::commit();

            session()->flash("order_success");
            session()->flash("order_number", $order->order_number);

            $this->redirectRoute("checkout.success", ["order" => $order]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Order creation failed", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
                "form_data" => $validatedData,
            ]);

            $this->addError(
                "order",
                __("store.Failed to create order. Please try again.")
            );
        }
    }

    public function updatedFormBillingCountry($value)
    {
        // Here you could load states/regions for the selected country
        // and update the shipping country if it's the same address
        if (!$this->form->different_shipping_address) {
            $this->form->shipping_country = $value;
        }
    }

    public function updatedFormDifferentShippingAddress($value)
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

    public function render()
    {
        if (count($this->cartItems) === 0) {
            $this->redirect(route("cart.index"));
        }

        return view("livewire.checkout-component");
    }
}
