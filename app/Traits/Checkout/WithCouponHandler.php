<?php

namespace App\Traits\Checkout;

use App\Services\Coupon\CouponService;
use Exception;
use Money\Money;

trait WithCouponHandler
{
    public ?string $couponError = null;
    protected ?Money $couponDiscount = null;
    protected CouponService $couponService;

    public function initializeWithCouponHandler(): void
    {
        $this->couponService = app(CouponService::class);
        $coupon = $this->cartService->getAppliedCoupon();
        if ($coupon) {
            $this->setCouponCode($coupon->code);
            try {
                $this->applyCoupon();
            } catch (Exception $e) {
                $this->couponError = $e->getMessage();
            }
        }
    }

    abstract protected function setCouponCode(?string $code): void;

    /**
     * @throws Exception
     */
    public function applyCoupon(): void
    {
        $this->couponError = null;

        $couponCode = $this->getCouponCode(); // Call abstract method

        if (empty($couponCode)) {
            $this->couponError = __("store.Please enter a coupon code");
            return;
        }

        $validation = $this->couponService->validateCoupon(
            $couponCode,
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

    abstract protected function getCouponCode(): ?string;

    // Abstract methods to be implemented in the component

    public function removeCoupon(): void
    {
        $this->setCouponCode(null); // Call setter method
        $this->couponDiscount = null;
        $this->cartService->removeCoupon();
        $this->dispatch("coupon-removed");
    }

    protected function getCouponDiscountAmount(): ?Money
    {
        return $this->couponDiscount;
    }
}
