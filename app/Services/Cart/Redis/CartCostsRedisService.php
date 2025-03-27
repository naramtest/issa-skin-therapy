<?php

namespace App\Services\Cart\Redis;

use App\Enums\Checkout\CartCostType;
use App\ValueObjects\AdditionalCost;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Money\Currency;
use Money\Money;

class CartCostsRedisService extends BaseRedisService
{
    private const COSTS_PREFIX = "test-cart:costs";

    public function removeCost(CartCostType $type): void
    {
        Redis::hdel($this->getCostsKey(), $type->value);
    }

    private function getCostsKey(): string
    {
        return $this->getKey(self::COSTS_PREFIX);
    }

    /**
     * @throws Exception
     */
    public function updateCost(CartCostType $type, Money $amount): void
    {
        $this->addCost($type, $amount);
    }

    /**
     * @throws Exception
     */
    public function addCost(CartCostType $type, Money $amount): void
    {
        try {
            $costData = [
                "type" => $type->value,
                "amount" => $amount->getAmount(),
                "currency" => $amount->getCurrency()->getCode(),
                "label" => $type->getLabel(),
                "taxable" => $type->isTaxable(),
                "subtract" => $type->isSubtract(),
            ];

            Redis::pipeline(function ($pipe) use ($type, $costData) {
                $pipe->hset(
                    $this->getCostsKey(),
                    $type->value,
                    json_encode($costData)
                );
                $pipe->expire($this->getCostsKey(), self::CART_EXPIRATION);
            });
        } catch (Exception $e) {
            throw new Exception(
                "Failed to add cost to cart: " . $e->getMessage()
            );
        }
    }

    public function getCost(CartCostType $type): ?AdditionalCost
    {
        try {
            $costJson = Redis::hget($this->getCostsKey(), $type->value);
            if (!$costJson) {
                return null;
            }

            $costData = json_decode($costJson, true);
            return new AdditionalCost(
                type: $costData["type"],
                amount: new Money(
                    $costData["amount"],
                    new Currency($costData["currency"])
                ),
                label: $costData["label"],
                taxable: $costData["taxable"],
                subtract: $costData["subtract"]
            );
        } catch (Exception $e) {
            return null;
        }
    }

    public function hasCost(CartCostType $type): bool
    {
        try {
            return Redis::hexists($this->getCostsKey(), $type->value);
        } catch (Exception) {
            return false;
        }
    }

    public function clear(): void
    {
        $this->clearKey($this->getCostsKey());
    }

    public function getCosts(): array
    {
        try {
            $costs = [];
            $costsData = Redis::hgetall($this->getCostsKey());

            foreach ($costsData as $type => $costJson) {
                $costData = json_decode($costJson, true);
                $costs[$type] = new AdditionalCost(
                    type: $costData["type"],
                    amount: new Money(
                        $costData["amount"],
                        new Currency($costData["currency"])
                    ),
                    label: $costData["label"],
                    taxable: $costData["taxable"],
                    subtract: $costData["subtract"]
                );
            }

            return $costs;
        } catch (Exception $e) {
            Log::error("Failed to get costs from cart", [
                "error" => $e->getMessage(),
                "cart_id" => $this->getCartId(),
            ]);
            return [];
        }
    }

    public function exists(): bool
    {
        try {
            return Redis::exists($this->getCostsKey()) > 0;
        } catch (Exception) {
            return false;
        }
    }
}
