<?php

namespace App\Services\Cart\Redis;

use Illuminate\Support\Facades\Redis;

abstract class BaseRedisService
{
    protected const CART_EXPIRATION = 604800; // 7 days

    abstract public function clear(): void;

    protected function getKey(string $prefix): string
    {
        return $prefix . ":" . $this->getCartId();
    }

    protected function getCartId(): string
    {
        if (auth()->check()) {
            return "user_" . auth()->id();
        }
        return session()->get("cart_id", function () {
            $cartId = "cart_" . uniqid();
            session()->put("cart_id", $cartId);
            return $cartId;
        });
    }

    protected function clearKey(string $key): void
    {
        Redis::del($key);
    }
}
