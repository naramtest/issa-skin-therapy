<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TrackingComponent extends Component
{
    #[Validate("required|min:3|string")]
    public string $orderId = "ORD-20250124-HTNIE";
    #[Validate("required|min:3|string|email")]
    public string $email = "naramalkoht123@gmail.com";

    public function render()
    {
        return view("livewire.tracking-component");
    }

    #[Computed]
    public function order()
    {
        return Order::query()
            ->with([
                "items" => function ($query) {
                    $query->with("purchasable");
                },

                "shippingOrder",
            ])
            ->where("order_number", $this->orderId)
            ->where("email", $this->email)
            ->first();
    }

    public function trackOrder()
    {
        $this->validate();
    }
}
