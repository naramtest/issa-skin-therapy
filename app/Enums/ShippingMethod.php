<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ShippingMethod: string implements HasLabel
{
    case EXPRESS_WORLDWIDE = "P";
    case DOMESTIC_EXPRESS = "N";

    public function toArray(): array
    {
        return [
            "productName" => $this->getLabel(),
            "productCode" => $this->value,
            "localProductCode" => $this->getLocalCode(),
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::DOMESTIC_EXPRESS => __("store.DOMESTIC EXPRESS"),
            self::EXPRESS_WORLDWIDE => __("store.EXPRESS WORLDWIDE"),
        };
    }

    public function getLocalCode(): string
    {
        return match ($this) {
            self::DOMESTIC_EXPRESS => "N",
            self::EXPRESS_WORLDWIDE => "P",
        };
    }
}
