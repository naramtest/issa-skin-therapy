<?php

namespace App\Enums\Checkout;

use Filament\Support\Contracts\HasLabel;

enum DHLProduct: string implements HasLabel
{
    case EXPRESS_WORLDWIDE = "WPX";
    case DOMESTIC_EXPRESS = "DOM";

    public static function getProduct(bool $isDomestic): DHLProduct
    {
        return $isDomestic ? self::DOMESTIC_EXPRESS : self::EXPRESS_WORLDWIDE;
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::DOMESTIC_EXPRESS => __("store.DOMESTIC EXPRESS"),
            self::EXPRESS_WORLDWIDE => __("store.EXPRESS WORLDWIDE"),
        };
    }

    public function getCommerceCode(): string
    {
        return match ($this) {
            self::DOMESTIC_EXPRESS => "DOM",
            self::EXPRESS_WORLDWIDE => "WPX",
        };
    }
}
