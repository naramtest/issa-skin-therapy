<?php

namespace App\Services\Cart\Redis;

use App\Models\Coupon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Money\Currency;
use Money\Money;

class CartCouponRedisService extends BaseRedisService
{
    private const COUPON_PREFIX = "test-cart:coupon";

    public function saveCoupon(Coupon $coupon, Money $discount): void
    {
        $this->removeCoupon();
        try {
            Redis::pipeline(function ($pipe) use ($coupon, $discount) {
                $pipe->hset($this->getCouponKey(), "coupon_id", $coupon->id);
                $pipe->hset(
                    $this->getCouponKey(),
                    "discount_amount",
                    $discount->getAmount()
                );
                $pipe->hset(
                    $this->getCouponKey(),
                    "discount_currency",
                    $discount->getCurrency()->getCode()
                );
                $pipe->expire($this->getCouponKey(), self::CART_EXPIRATION);
            });
        } catch (Exception $e) {
            Log::error("Failed to save coupon", [
                "error" => $e->getMessage(),
                "coupon_code" => $coupon->code,
            ]);
            throw $e;
        }
    }

    public function removeCoupon(): void
    {
        $this->clearKey($this->getCouponKey());
    }

    private function getCouponKey(): string
    {
        return $this->getKey(self::COUPON_PREFIX);
    }

    public function getCoupon(): ?array
    {
        try {
            $couponData = Redis::hgetall($this->getCouponKey());

            if (empty($couponData)) {
                return null;
            }

            $coupon = Coupon::find($couponData["coupon_id"]);
            if (!$coupon) {
                return null;
            }

            return [
                "coupon" => $coupon,
                "discount" => new Money(
                    $couponData["discount_amount"],
                    new Currency($couponData["discount_currency"])
                ),
            ];
        } catch (Exception $e) {
            Log::error("Failed to get coupon from redis", [
                "error" => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function clear(): void
    {
        $this->clearKey($this->getCouponKey());
    }

    public function exists(): bool
    {
        return Redis::exists($this->getCouponKey()) > 0;
    }
}
