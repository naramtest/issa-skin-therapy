<?php

namespace App\Services\Cart;

use App\Enums\Checkout\CartCostType;
use App\Enums\ProductType;
use App\Models\Bundle;
use App\Models\Product;
use Exception;
use Log;
use Money\Money;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

readonly class CartService
{
    //    TODO: add USer Currency to the final order with the current currency conversion rate
    private CartRedisService $redisService;

    public function __construct(
        private CartPriceCalculator $priceCalculator,
        private CartCostsManager $costsManager
    ) {
        $this->redisService = new CartRedisService($this->resolveCartId());
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
        } catch (ContainerExceptionInterface | NotFoundExceptionInterface $e) {
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

    public function getId(): string
    {
        return $this->resolveCartId();
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

        $this->redisService->addItem($purchasable, $quantity, $options);
    }

    public function removeItem(string $itemId): void
    {
        $this->redisService->removeItem($itemId);
    }

    /**
     * @throws Exception
     */
    public function updateItemQuantity(string $itemId, int $quantity): void
    {
        $item = $this->redisService->getItem($itemId);

        if (!$item) {
            throw new Exception(__("store.Item not found in cart"));
        }

        if (!$item->getPurchasable()->inventory()->canBePurchased($quantity)) {
            throw new Exception(__("store.Insufficient stock"));
        }

        $this->redisService->updateItem($itemId, $quantity);
    }

    public function clear(): void
    {
        $this->redisService->clear();
        $this->costsManager->clearCosts();
    }

    public function isEmpty(): bool
    {
        return !$this->exists();
    }

    public function exists(): bool
    {
        return $this->redisService->exists();
    }

    public function itemCount(): int
    {
        return $this->redisService->count();
    }

    public function getTotal(): Money
    {
        return $this->priceCalculator->calculateTotal($this->getItems());
    }

    public function getItems(): array
    {
        return $this->redisService->getItems();
    }

    public function getSubtotal(): Money
    {
        return $this->priceCalculator->calculateSubtotal($this->getItems());
    }

    public function addCost(CartCostType $type, Money $amount): void
    {
        $this->costsManager->addCost($type, $amount);
    }

    public function removeCost(CartCostType $type): void
    {
        $this->costsManager->removeCost($type);
    }

    public function getAdditionalCosts(): array
    {
        return $this->costsManager->getCosts();
    }
}
