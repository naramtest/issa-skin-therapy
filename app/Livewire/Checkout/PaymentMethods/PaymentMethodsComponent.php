<?php

namespace App\Livewire\Checkout\PaymentMethods;

use Livewire\Component;

class PaymentMethodsComponent extends Component
{
    public float $price = 0;

    public function mount(float $price)
    {
        $this->price = $price;
    }

    public function updatedSelectedMethod($value)
    {
        $this->dispatch("payment-method-changed", $value);
    }

    public function render()
    {
        return view(
            "livewire.checkout.payment-methods.payment-methods-component"
        );
    }
}
