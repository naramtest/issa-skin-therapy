<?php

namespace App\Traits\Checkout;

use Exception;
use Money\Money;

trait WithCouponHandler
{
    public ?string $couponError = null;
    protected ?Money $couponDiscount = null;

    public function initializeWithCouponHandler(): void
    {
        $coupon = $this->cartService->getAppliedCoupon(); // TODO: in the checkout try to get the coupon once
        if ($coupon) {
            $this->setCouponCode($coupon->code);
            try {
                $this->applyCoupon(); // TODO : this line resave the coupon every time try to get the coupon without saving it
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
