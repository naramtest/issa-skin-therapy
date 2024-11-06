<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class CartComponent extends Component
{
    public $isOpen = false;
    public $cartItems = [];
    public $subtotal = 0;

    public function mount()
    {
        $this->cartItems = [
            [
                "id" => 1,
                "name" => "X-AGE Stem Booster",
                "price" => 195.47,
                "quantity" => 1,
                "image" => asset("storage/test/product/product.webp"),
                "subtitle" => "TREAT, X-AGE",
            ],
            [
                "id" => 2,
                "name" => "A-Clear Control lotion",
                "price" => 63.5,
                "quantity" => 2,
                "image" => asset("storage/test/product/product.webp"),

                "subtitle" => "A-Clear, TREAT",
            ],
            [
                "id" => 3,
                "name" => "A-Luminate Renewing Lotion",
                "price" => 59.0,
                "quantity" => 1,
                "image" => asset("storage/test/product/product.webp"),

                "subtitle" => "HYDRATE & PROTECT",
            ],
            [
                "id" => 4,
                "name" => "LumiGuard Broad Spectrum Emulsion",
                "price" => 59.76,
                "quantity" => 1,
                "image" => asset("storage/test/product/product.webp"),

                "subtitle" => "A-Luminate, HYDRATE & PROTECT",
            ],
        ];

        $this->calculateSubtotal();
    }

    private function calculateSubtotal()
    {
        $this->subtotal = array_reduce(
            $this->cartItems,
            function ($carry, $item) {
                return $carry + $item["price"] * $item["quantity"];
            },
            0
        );
    }

    #[On("toggle-cart")]
    public function toggleCart()
    {
        $this->isOpen = !$this->isOpen;
    }

    #[On("add-to-cart")]
    public function addToCart($product)
    {
        // Add your cart logic here
        $this->dispatch("cart-updated");
    }

    public function updateQuantity($itemId, $action)
    {
        // Find the item in the cart
        $index = array_search($itemId, array_column($this->cartItems, "id"));

        if ($action === "increment") {
            $this->cartItems[$index]["quantity"]++;
        } elseif (
            $action === "decrement" &&
            $this->cartItems[$index]["quantity"] > 1
        ) {
            $this->cartItems[$index]["quantity"]--;
        }

        $this->calculateSubtotal();
    }

    public function removeItem($itemId)
    {
        $this->cartItems = array_filter($this->cartItems, function ($item) use (
            $itemId
        ) {
            return $item["id"] !== $itemId;
        });

        $this->calculateSubtotal();
    }

    public function render()
    {
        return view("livewire.cart-component");
    }
}
