<?php

namespace App\Services\Cart;

use App\Enums\Checkout\CartCostType;
use App\Enums\ProductType;
use App\Models\Bundle;
use App\Models\Coupon;
use App\Models\Product;
use App\Services\Cart\Redis\CartCostsRedisService;
use App\Services\Cart\Redis\CartCouponRedisService;
use App\Services\Cart\Redis\CartItemsRedisService;
use Exception;
use Log;
use Money\Money;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

readonly class CartService
{
    public function __construct(
        private CartPriceCalculator $priceCalculator,
        private CartItemsRedisService $itemsService,
        private CartCostsRedisService $costsService,
        private CartCouponRedisService $couponService
    ) {
    }

    /**
     * @throws Exception
     */
    public function applyCoupon(Coupon $coupon, Money $discount): void
    {
        $this->couponService->saveCoupon($coupon, $discount);
        $this->addCost(CartCostType::DISCOUNT, $discount);
    }

    /**
     * @throws Exception
     */
    public function addCost(CartCostType $type, Money $amount): void
    {
        $this->costsService->addCost($type, $amount);
    }

    public function removeCoupon(): void
    {
        $this->couponService->removeCoupon();
        $this->removeCost(CartCostType::DISCOUNT);
    }

    public function removeCost(CartCostType $type): void
    {
        $this->costsService->removeCost($type);
    }

    public function getAppliedCoupon(): ?Coupon
    {
        $couponData = $this->couponService->getCoupon();
        return $couponData ? $couponData["coupon"] : null;
    }

    public function getCouponDiscount(): ?Money
    {
        $couponData = $this->couponService->getCoupon();
        return $couponData ? $couponData["discount"] : null;
    }

    public function getId(): string
    {
        return $this->resolveCartId();
    }

    private function resolveCartId(): string
    {
        if (auth()->check()) {
            return "user_" . auth()->id();
        }

        try {
            return session()->get("cart_id", function () {
                return $this->generateAndStoreNewCartId();
            });
        } catch (ContainerExceptionInterface | NotFoundExceptionInterface) {
            return $this->generateAndStoreNewCartId();
        }
    }

    private function generateAndStoreNewCartId(): string
    {
        $cartId = "cart_" . uniqid();

        try {
            session()->put("cart_id", $cartId);
        } catch (Exception $e) {
            Log::error("Failed to store cart ID in session", [
                "cart_id" => $cartId,
                "error" => $e->getMessage(),
            ]);
        }

        return $cartId;
    }

    /**
     * @throws Exception
     */
    public function addItem(
        ProductType $type,
        int $id,
        int $quantity = 1,
        array $options = []
    ): void {
        $purchasable = match ($type) {
            ProductType::PRODUCT => Product::findOrFail($id),
            ProductType::BUNDLE => Bundle::findOrFail($id),
        };

        // Validate inventory
        if (!$purchasable->inventory()->canBePurchased($quantity)) {
            throw new Exception(__("store.Insufficient stock"));
        }

        $this->itemsService->addItem($purchasable, $quantity, $options);
    }

    public function removeItem(string $itemId): void
    {
        $this->itemsService->removeItem($itemId);
    }

    /**
     * @throws Exception
     */
    public function updateItemQuantity(string $itemId, int $quantity): void
    {
        $item = $this->itemsService->getItem($itemId);

        if (!$item) {
            throw new Exception(__("store.Item not found in cart"));
        }

        if (!$item->getPurchasable()->inventory()->canBePurchased($quantity)) {
            throw new Exception(__("store.Insufficient stock"));
        }

        $this->itemsService->updateItem($itemId, $quantity);
    }

    public function isEmpty(): bool
    {
        return !$this->itemsService->exists();
    }

    public function itemCount(): int
    {
        return $this->itemsService->count();
    }

    public function getTotal(): Money
    {
        return $this->priceCalculator->calculateTotal($this->getItems());
    }

    public function getItems(): array
    {
        return $this->itemsService->getItems();
    }

    public function getSubtotal(): Money
    {
        return $this->priceCalculator->calculateSubtotal($this->getItems());
    }

    public function getAdditionalCosts(): array
    {
        return $this->costsService->getCosts();
    }

    public function clearItems(): void
    {
        $this->itemsService->clear();
    }

    public function clear(): void
    {
        $this->itemsService->clear();
        $this->costsService->clear();
        $this->couponService->clear();
    }

    // Clear only costs

    public function clearCosts(): void
    {
        $this->costsService->clear();
    }
}
