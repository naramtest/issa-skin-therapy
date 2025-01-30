<?php

namespace App\Enums\Checkout;

use Filament\Support\Contracts\HasLabel;

enum ShippingMethodType: string implements HasLabel
{
    case FLAT_RATE = "flat_rate";
    case FREE_SHIPPING = "free_shipping";
    case LOCAL_PICKUP = "local_pickup";

    //    case DHL_EXPRESS = "dhl_express";

    public function getLabel(): string
    {
        return match ($this) {
            self::FLAT_RATE => __("store.Flat Rate"),
            self::FREE_SHIPPING => __("store.Free Shipping"),
            self::LOCAL_PICKUP => __(
                "store.Local Pickup"
            ), //            self::DHL_EXPRESS => __("store.DHL Express"),
        };
    }
}
