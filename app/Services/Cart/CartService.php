<?php

namespace App\Services\Cart;

use App\Enums\Checkout\CartCostType;
use App\Enums\ProductType;
use App\Models\Bundle;
use App\Models\Product;
use App\Services\Currency\CurrencyHelper;
use App\ValueObjects\AdditionalCost;
use App\ValueObjects\CartItem;
use Exception;
use Log;
use Money\Money;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CartService
{
    private array $additionalCosts = [];
    //    TODO: add USer Currency to the final order with the current currency conversion rate
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
        } catch (Exception $e) {
            Log::error("Failed to store cart ID in session", [
                "cart_id" => $cartId,
                "error" => $e->getMessage(),
            ]);
        }

        return $cartId;
    }

    public function addCost(CartCostType $type, Money $amount): void
    {
        $this->additionalCosts[$type->value] = new AdditionalCost(
            type: $type->value,
            amount: $amount,
            label: $type->getLabel(),
            taxable: $type->isTaxable()
        );
    }

    public function removeCost(CartCostType $type): void
    {
        unset($this->additionalCosts[$type->value]);
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
        // 1. Start with subtotal
        $total = $this->getSubtotal();
        $taxableAmount = $this->getSubtotal();

        // 2. Process all costs first, keeping track of taxable amount
        foreach ($this->additionalCosts as $cost) {
            // Add to total
            $total = $total->add($cost->amount);

            // If taxable, add to taxable amount for tax calculation
            if ($cost->taxable) {
                $taxableAmount = $taxableAmount->add($cost->amount);
            }
        }

        // 3. Calculate and add tax based on total taxable amount
        $tax = $this->calculateTax($taxableAmount);
        if ($tax) {
            $total = $total->add($tax);
        }

        return $total;
    }

    public function getSubtotal(): Money
    {
        $initial = new Money(0, CurrencyHelper::defaultCurrency());
        try {
            return array_reduce(
                $this->getItems(),
                fn(Money $carry, CartItem $item) => $carry->add(
                    $item->getSubtotal()
                ),
                $initial
            );
        } catch (Exception) {
            return $initial;
        }
    }

    public function getItems(): array
    {
        try {
            return $this->redisService->getItems();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return [];
        }
    }

    protected function calculateTax(Money $taxableAmount): ?Money
    {
        //        TODO: taxes
        //        $taxRate = $this->getTaxRate();
        //        if ($taxRate <= 0) {
        //            return null;
        //        }

        //        return $taxableAmount->multiply($taxRate);
        return new Money(0, CurrencyHelper::defaultCurrency());
    }

    public function getAdditionalCosts(): array
    {
        return $this->additionalCosts;
    }
}
