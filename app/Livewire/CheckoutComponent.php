<?php

namespace App\Livewire;

use App\Livewire\Forms\CheckoutForm;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Services\Cart\CartService;
use App\Services\Checkout\CheckoutService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Log;

class CheckoutComponent extends Component
{
    public CheckoutForm $form;

    protected CartService $cartService;
    protected CheckoutService $checkoutService;

    public function boot(
        CartService $cartService,
        CheckoutService $checkoutService
    ) {
        $this->cartService = $cartService;
        $this->checkoutService = $checkoutService;
    }

    public function mount()
    {
        if (auth()->check()) {
            $this->form->setFromUser(auth()->user());
        }
    }

    #[Computed]
    public function cartItems()
    {
        try {
            return $this->cartService->getItems();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
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

    public function placeOrder()
    {
        dd($this->form->validate());

        dd("naram");
        try {
            DB::beginTransaction();

            // Create or get customer
            $customer = $this->getOrCreateCustomer();

            // Create shipping address
            $shippingAddress = $this->createAddress(
                $customer,
                $this->form->getShippingAddressData()
            );

            // Create billing address if different
            $billingAddress = $this->form->different_billing_address
                ? $this->createAddress(
                    $customer,
                    $this->form->getBillingAddressData()
                )
                : $shippingAddress;

            // Create the order
            $order = $this->checkoutService->createOrder(
                customer: $customer,
                billingAddress: $billingAddress,
                shippingAddress: $shippingAddress,
                notes: $this->form->order_notes
            );

            if ($this->form->create_account && !auth()->check()) {
                // Handle account creation logic here
            }

            DB::commit();

            // Clear the cart
            $this->cartService->clear();

            // Redirect to success page
            return $this->redirect(
                route("checkout.success", ["order" => $order]),
                navigate: true
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError(
                "order",
                __("store.Failed to create order. Please try again.")
            );
        }
    }

    protected function getOrCreateCustomer(): Customer
    {
        if (auth()->check()) {
            return auth()->user()->customer;
        }

        return Customer::firstOrCreate(
            ["email" => $this->form->email],
            [
                "first_name" => $this->form->first_name,
                "last_name" => $this->form->last_name,
                "name" =>
                    $this->form->first_name . " " . $this->form->last_name,
                "is_registered" => false,
            ]
        );
    }

    protected function createAddress(
        Customer $customer,
        array $addressData
    ): CustomerAddress {
        return CustomerAddress::create(
            array_merge($addressData, [
                "customer_id" => $customer->id,
                "is_default" => !$customer->addresses()->exists(),
            ])
        );
    }
}
