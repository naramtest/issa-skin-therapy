<?php

namespace App\Livewire;

use App\Services\Cart\CartService;
use Exception;
use Livewire\Component;
use Money\Money;

class CartPage extends Component
{
    public $cartItems = [];
    public string $subtotalString = "";

    protected Money $subtotal;
    protected Money $total;
    protected CartService $cartService;

    public function boot(CartService $cartService): void
    {
        $this->cartService = $cartService;
    }

    public function updateQuantity(string $itemId, string $action): void
    {
        try {
            $item = $this->cartItems[$itemId] ?? null;
            if (!$item) {
                throw new Exception(__("store.Item not found in cart"));
            }

            $currentQty = $item->getQuantity();
            $newQty =
                $action === "increment" ? $currentQty + 1 : $currentQty - 1;

            if ($newQty < 1) {
                $this->removeItem($itemId);
                return;
            }

            $this->cartService->updateItemQuantity($itemId, $newQty);
            $this->refreshCart();
            $this->dispatch("cart-updated");
        } catch (Exception $e) {
            $this->dispatch("error", message: $e->getMessage());
        }
    }

    public function removeItem(string $itemId): void
    {
        try {
            $this->cartService->removeItem($itemId);
            $this->refreshCart();
            $this->dispatch("cart-updated");
        } catch (\Exception $e) {
            $this->dispatch("error", message: $e->getMessage());
        }
    }

    protected function refreshCart(): void
    {
        try {
            $this->cartItems = $this->cartService->getItems();
            $this->subtotal = $this->cartService->getSubtotal();
            $this->total = $this->cartService->getTotal();
        } catch (Exception $e) {
            $this->dispatch(
                "error",
                message: "Failed to load cart: " . $e->getMessage()
            );
        }
    }

    public function mount(): void
    {
        $this->refreshCart();
    }

    public function render()
    {
        return view("livewire.cart-page", [
            "subtotal" => $this->subtotal,
            "total" => $this->total,
        ]);
    }
}
