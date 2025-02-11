<?php

namespace App\Livewire\Checkout\PaymentMethods;

use Livewire\Component;

class TabbyPaymentMethod extends Component
{
    public bool $isSelected = true;
    public ?string $error = null;
    public bool $isAvailable = false;
    public ?string $rejectionReason = null;
    public float $price = 0;

    public function mount(
        float $price,
        $isAvailable = false,
        $rejectionReason = null
    ) {
        $this->price = $price;
        $this->isAvailable = $isAvailable;
        $this->rejectionReason = $rejectionReason;
    }

    public function handlePaymentMethodChange($method)
    {
        $this->isSelected = $method === "tabby";
    }

    public function render()
    {
        return view("livewire.checkout.payment-methods.tabby-payment-method");
    }
}
