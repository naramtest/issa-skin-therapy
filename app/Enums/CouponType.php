<?php

namespace App\Enums;

enum CouponType: string
{
    case FIXED = "fixed";
    case PERCENTAGE = "percentage";
    case SHIPPING = "shipping";

    public function getLabel(): string
    {
        return match ($this) {
            self::FIXED => __("store.Fixed Amount"),
            self::PERCENTAGE => __("store.Percentage"),
            self::SHIPPING => __("store.Free Shipping"),
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::FIXED => __("store.A fixed amount discount"),
            self::PERCENTAGE => __("store.A percentage of cart total"),
            self::SHIPPING => __("store.Free shipping on order"),
        };
    }
}
