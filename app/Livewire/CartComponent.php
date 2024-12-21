<?php

namespace App\Livewire;

use App\Enums\ProductType;
use App\Services\Cart\CartService;
use Exception;
use Livewire\Attributes\On;
use Livewire\Component;

class CartComponent extends Component
{
    public $isOpen = false;
    public $cartItems = [];
    public $subtotal = 0;
    public $total = 0;
    public $itemCount = 0;

    protected CartService $cartService;

    public function boot(CartService $cartService): void
    {
        $this->cartService = $cartService;
    }

    public function mount(): void
    {
        $this->refreshCart();
    }

    protected function refreshCart(): void
    {
        try {
            $this->cartItems = $this->cartService->getItems();
            $this->subtotal = $this->cartService->getSubtotal()->getAmount();
            $this->total = $this->cartService->getTotal()->getAmount();
            $this->itemCount = $this->cartService->itemCount();
        } catch (Exception $e) {
            $this->dispatch(
                "error",
                message: "Failed to load cart: " . $e->getMessage()
            );
        }
    }

    #[On("toggle-cart")]
    public function toggleCart(): void
    {
        $this->isOpen = !$this->isOpen;
    }

    #[On("add-to-cart")]
    public function addToCart(
        string $type,
        $id,
        $quantity = 1,
        $options = []
    ): void {
        try {
            $this->cartService->addItem(
                type: ProductType::fromString($type),
                id: $id,
                quantity: $quantity,
                options: $options
            );

            $this->refreshCart();
            $this->isOpen = true;
            $this->dispatch("cart-updated");
            //TODO: for notification
            $this->dispatch(
                "success",
                message: __("store.Item added to cart successfully")
            );
        } catch (Exception $e) {
            $this->dispatch("error", message: $e->getMessage());
        }
    }

    public function updateQuantity(string $itemId, string $action): void
    {
        try {
            $item = $this->cartService->getItems()[$itemId] ?? null;
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

    public function render()
    {
        return view("livewire.cart-component");
    }

    public function clearCart(): void
    {
        try {
            $this->cartService->clear();
            $this->refreshCart();
            $this->dispatch("cart-updated");
        } catch (\Exception $e) {
            $this->dispatch("error", message: $e->getMessage());
        }
    }
}
