<?php

namespace App\Services\Coupon;

use App\Enums\CouponType;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Customer;
use App\Models\Order;
use App\Services\Affiliate\AffiliateService;
use App\Services\Currency\Currency;
use App\Services\Currency\CurrencyHelper;
use Money\Money;

readonly class CouponService
{
    public function __construct(protected AffiliateService $affiliateService)
    {
    }

    public function validateCoupon(string $code, Money $cartTotal): array
    {
        $coupon = Coupon::where("code", $code)->first();

        if (!$coupon) {
            return [
                "valid" => false,
                "message" => __("store.Invalid coupon code"),
            ];
        }

        if (!$coupon->isValid()) {
            return [
                "valid" => false,
                "message" => __("store.This coupon is no longer valid"),
            ];
        }

        if (
            $coupon->minimum_spend &&
            $cartTotal->lessThan($coupon->money_minimum_spend)
        ) {
            return [
                "valid" => false,
                "message" => __("store.Minimum spend of :amount required", [
                    "amount" => CurrencyHelper::format(
                        Currency::convertToUserCurrencyWithCache(
                            $coupon->money_minimum_spend
                        )
                    ),
                ]),
            ];
        }

        if (
            $coupon->maximum_spend &&
            $cartTotal->greaterThan($coupon->money_maximum_spend)
        ) {
            return [
                "valid" => false,
                "message" => __("store.Maximum spend of :amount exceeded", [
                    "amount" => CurrencyHelper::format(
                        Currency::convertToUserCurrencyWithCache(
                            $coupon->money_maximum_spend
                        )
                    ),
                ]),
            ];
        }

        return [
            "valid" => true,
            "coupon" => $coupon,
        ];
    }

    public function calculateDiscount(Coupon $coupon, Money $cartTotal): Money
    {
        return match ($coupon->discount_type) {
            CouponType::FIXED => new Money(
                $coupon->discount_amount,
                CurrencyHelper::defaultCurrency()
            ),
            CouponType::PERCENTAGE => $cartTotal
                ->multiply($coupon->discount_amount)
                ->divide(100),
            CouponType::SHIPPING => Money::USD(
                0
            ), // We'll handle shipping discounts separately
        };
    }

    public function validateShippingEligibility(
        Coupon $coupon,
        string $countryCode
    ): bool {
        if (!$coupon->includes_free_shipping) {
            return false;
        }
        if (
            !empty($coupon->allowed_shipping_countries) &&
            !in_array($countryCode, $coupon->allowed_shipping_countries)
        ) {
            return false;
        }

        return true;
    }

    public function recordUsage(
        Coupon $coupon,
        Order $order,
        Customer $customer,
        Money $discount
    ): void {
        CouponUsage::create([
            "coupon_id" => $coupon->id,
            "order_id" => $order->id,
            "customer_id" => $customer->id,
            "discount_amount" => $discount->getAmount(),
            "used_at" => now(),
        ]);

        $coupon->incrementUsage();

        // If this is an affiliate coupon, track commission
        if ($coupon->isAffiliateCoupon()) {
            $this->affiliateService->trackCommission($order, $coupon);
        }
    }
}
