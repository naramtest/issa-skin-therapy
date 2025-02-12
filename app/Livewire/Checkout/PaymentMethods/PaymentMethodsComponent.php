<?php

namespace App\Livewire\Checkout\PaymentMethods;

use Livewire\Component;

class PaymentMethodsComponent extends Component
{
    public float $total = 0;
    public float $stripeAmount = 0;
    public ?string $error;
    public string $selectedMethod;
    public ?string $rejectionReason;
    public bool $isAvailable;

    public function mount(
        float $total,
        ?string $error,
        string $selectedMethod,
        ?string $rejectionReason,
        bool $isAvailable
    ): void {
        $this->total = $total;
        $this->error = $error;
        $this->selectedMethod = $selectedMethod;
        $this->rejectionReason = $rejectionReason;
        $this->isAvailable = $isAvailable;
    }

    public function render()
    {
        return view(
            "livewire.checkout.payment-methods.payment-methods-component"
        );
    }
}
