<?php

namespace App\Enums\Checkout;

enum CartCostType: string
{
    case SHIPPING = "shipping";
    case TAX = "tax";
    case HANDLING = "handling";
    case DISCOUNT = "discount";
    case INSURANCE = "insurance";

    public function getLabel(): string
    {
        return match ($this) {
            self::SHIPPING => __("store.Shipping"),
            self::TAX => __("store.Tax"),
            self::HANDLING => __("store.Handling Fee"),
            self::DISCOUNT => __("store.Discount"),
            self::INSURANCE => __("store.Insurance"),
        };
    }

    public function isTaxable(): bool
    {
        return match ($this) {
            self::HANDLING, self::INSURANCE => true,
            default => false,
        };
    }

    public function isSubtract(): bool
    {
        return match ($this) {
            self::DISCOUNT => true,
            default => false,
        };
    }
}
