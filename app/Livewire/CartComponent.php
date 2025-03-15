<?php

namespace App\Livewire;

use App\Enums\ProductType;
use App\Services\Cart\CartService;
use App\Services\Currency\CurrencyHelper;
use App\Traits\Checkout\WithCouponHandler;
use Exception;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Money\Money;

class CartComponent extends Component
{
    use WithCouponHandler;

    public $isOpen = false;
    public string $subtotalString = "";
    public $itemCount = 0;
    public ?string $coupon_code;

    protected Money $subtotal;
    protected Money $total;
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
            $this->subtotal = $this->cartService->getSubtotal();
            $this->total = $this->cartService->getTotal();

            //I have to do that because I can push money object to livewire
            $this->subtotalString = CurrencyHelper::moneyObjectInBlade(
                $this->subtotal
            );

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
            $result = $this->cartService->addItem(
                type: ProductType::fromString($type),
                id: $id,
                quantity: $quantity,
                options: $options
            );

            $this->refreshCart();

            $this->isOpen = true;
            $this->dispatch("toggle-cart");
            $this->dispatch("finish-loading");

            $price = CurrencyHelper::decimalFormatter(
                $result["item"]->current_money_price
            );
            $this->facebookAddToCartEvent($result["item"], $quantity, $price);
        } catch (Exception $e) {
            $this->dispatch("error", message: $e->getMessage());
        }
    }

    /**
     * @param $item
     * @param mixed $quantity
     * @param string $price
     * @return void
     */
    public function facebookAddToCartEvent(
        $item,
        mixed $quantity,
        string $price
    ): void {
        $currency = CurrencyHelper::getCurrencyCode();

        // Push to the data layer
        $this->js(
            "
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                'event': 'addToCart',
               'contents': [
                    { id: '{$item->facebook_id}', quantity: {$quantity} }
                ],
                'contents_tiktok': [
                    { content_id: '{$item->facebook_id}', quantity: {$quantity} ,content_name: '{$item->name}' ,price:{$price}}
                ],
                'content_type': 'product',
               'value': {$price},
                'currency': '{$currency}'
            });
        "
        );
    }

    public function updateQuantity(string $itemId, string $action): void
    {
        try {
            $cartItems = $this->cartItems();
            $item = $cartItems[$itemId] ?? null;
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
        } catch (Exception $e) {
            $this->dispatch("error", message: $e->getMessage());
        }
    }

    #[Computed]
    public function cartItems()
    {
        return $this->cartService->getItems();
    }

    public function removeItem(string $itemId): void
    {
        try {
            $this->cartService->removeItem($itemId);
            $this->refreshCart();
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
        } catch (\Exception $e) {
            $this->dispatch("error", message: $e->getMessage());
        }
    }

    public function getCouponCode(): ?string
    {
        return $this->coupon_code;
    }

    public function setCouponCode(?string $code): void
    {
        $this->coupon_code = $code;
    }

    #[Computed]
    public function discount(): ?Money
    {
        return $this->getCouponDiscountAmount();
    }
}
