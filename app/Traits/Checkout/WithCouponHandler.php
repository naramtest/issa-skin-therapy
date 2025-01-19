<?php

namespace App\Traits\Checkout;

use App\Services\Coupon\CouponService;
use Money\Money;

trait WithCouponHandler
{
    public ?string $couponError = null;
    protected ?Money $couponDiscount = null;
    protected CouponService $couponService;

    public function initializeWithCouponHandler(): void
    {
        $this->couponService = app(CouponService::class);
    }

    public function applyCoupon(): void
    {
        $this->couponError = null;

        if (empty($this->form->coupon_code)) {
            $this->couponError = __("store.Please enter a coupon code");
            return;
        }

        $validation = $this->couponService->validateCoupon(
            $this->form->coupon_code,
            $this->cartService->getSubtotal()
        );

        if (!$validation["valid"]) {
            $this->couponError = $validation["message"];
            return;
        }

        $coupon = $validation["coupon"];
        $this->couponDiscount = $this->couponService->calculateDiscount(
            $coupon,
            $this->cartService->getSubtotal()
        );

        $this->cartService->applyCoupon($coupon, $this->couponDiscount);
        $this->dispatch("coupon-applied");
    }

    public function removeCoupon(): void
    {
        $this->form->coupon_code = null;
        $this->couponDiscount = null;
        $this->cartService->removeCoupon();
        $this->dispatch("coupon-removed");
    }

    protected function getCouponDiscountAmount(): ?Money
    {
        return $this->couponDiscount;
    }
}
