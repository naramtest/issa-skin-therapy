<?php

namespace App\Services\Cart;

use App\Contracts\CartInterface;
use App\Models\Product;
use App\ValueObjects\CartItem;
use Exception;
use Log;
use Money\Currency;
use Money\Money;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CartService implements CartInterface
{
    private CartRedisService $redisService;

    public function __construct()
    {
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
        } catch (\Exception $e) {
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
        string $productId,
        int $quantity = 1,
        array $options = []
    ): void {
        $product = Product::findOrFail($productId);

        // Business logic validation
        if (!$product->inventory()->canBePurchased($quantity)) {
            throw new Exception("Insufficient stock");
        }

        if (!$product->isPublished()) {
            throw new Exception("Product is not available for purchase");
        }

        $this->redisService->addItem($product, $quantity, $options);

        // Additional business logic
        // event(new ItemAddedToCart($product, $quantity));
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
            throw new Exception("Item not found in cart");
        }

        if (!$item->getProduct()->inventory()->canBePurchased($quantity)) {
            throw new Exception("Insufficient stock");
        }

        $this->redisService->updateItem($itemId, $quantity);
    }

    public function clear(): void
    {
        $this->redisService->clear();
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
        //TODO:add tax and shipping calculations later
        return $this->getSubtotal();
    }

    public function getSubtotal(): Money
    {
        //        TODO: get user selected Currency
        return array_reduce(
            $this->getItems(),
            fn(Money $carry, CartItem $item) => $carry->add(
                $item->getSubtotal()
            ),
            new Money(0, new Currency(config("app.currency")))
        );
    }

    public function getItems(): array
    {
        return $this->redisService->getItems();
    }
}
