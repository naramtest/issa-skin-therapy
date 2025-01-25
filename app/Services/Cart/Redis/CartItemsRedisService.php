<?php

namespace App\Services\Cart\Redis;

use App\Contracts\Purchasable;
use App\Models\Bundle;
use App\Models\Product;
use App\ValueObjects\CartItem;
use Exception;
use Illuminate\Support\Facades\Redis;

class CartItemsRedisService extends BaseRedisService
{
    private const ITEMS_PREFIX = "cart:items";

    /**
     * @throws Exception
     */
    public function addItem(
        Purchasable $purchasable,
        int $quantity,
        array $options = []
    ): void {
        try {
            $itemId = $this->generateItemId(
                get_class($purchasable),
                $purchasable->getId(),
                $options
            );

            Redis::pipeline(function ($pipe) use (
                $itemId,
                $purchasable,
                $quantity,
                $options
            ) {
                // Store item data
                $pipe->hset(
                    $this->getItemsKey(),
                    $itemId,
                    $this->serializeItem($purchasable, $quantity, $options)
                );

                // Reset expiration
                $pipe->expire($this->getItemsKey(), self::CART_EXPIRATION);
            });
        } catch (Exception $e) {
            throw new Exception(
                "Failed to add item to cart: " . $e->getMessage()
            );
        }
    }

    private function generateItemId(
        string $type,
        int $id,
        array $options
    ): string {
        return md5($type . $id . serialize($options));
    }

    private function getItemsKey(): string
    {
        return $this->getKey(self::ITEMS_PREFIX);
    }

    private function serializeItem(
        Purchasable $purchasable,
        int $quantity,
        array $options
    ): string {
        return serialize([
            "purchasable_type" => get_class($purchasable), // Store the class type
            "purchasable_id" => $purchasable->getId(),
            "quantity" => $quantity,
            "options" => $options,
        ]);
    }

    public function removeItem(string $itemId): void
    {
        Redis::hdel($this->getItemsKey(), $itemId);
    }

    /**
     * @throws Exception
     */
    public function updateItem(string $itemId, int $quantity): void
    {
        $item = $this->getItem($itemId);
        if ($item) {
            Redis::hset(
                $this->getItemsKey(),
                $itemId,
                $this->serializeItem(
                    $item->getPurchasable(),
                    $quantity,
                    $item->getOptions()
                )
            );
        }
    }

    /**
     * @throws Exception
     */
    public function getItem(string $itemId): ?CartItem
    {
        $data = Redis::hget($this->getItemsKey(), $itemId);
        return $data ? $this->unserializeItem($data) : null;
    }

    /**
     * @throws Exception
     */
    private function unserializeItem(string $data): CartItem
    {
        $data = unserialize($data);

        // Load the correct type (Product or Bundle)
        $purchasable = match ($data["purchasable_type"]) {
            Product::class => Product::find($data["purchasable_id"]),
            Bundle::class => Bundle::find($data["purchasable_id"]),
            default => throw new Exception("Invalid purchasable type"),
        };

        if (!$purchasable) {
            throw new Exception("Purchasable item not found");
        }

        return new CartItem(
            $this->generateItemId(
                $data["purchasable_type"],
                $data["purchasable_id"],
                $data["options"]
            ),
            $purchasable,
            $data["quantity"],
            $data["options"]
        );
    }

    public function clear(): void
    {
        $this->clearKey($this->getItemsKey());
    }

    public function exists(): bool
    {
        return Redis::exists($this->getItemsKey());
    }

    public function count(): int
    {
        return Redis::hlen($this->getItemsKey());
    }

    public function getItems(): array
    {
        $cartData = Redis::hgetall($this->getItemsKey());
        try {
            return array_map(
                fn($item) => $this->unserializeItem($item),
                $cartData
            );
        } catch (Exception) {
            return [];
        }
    }
}
