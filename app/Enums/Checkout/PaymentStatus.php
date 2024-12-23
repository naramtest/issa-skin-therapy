<?php

namespace App\Enums\Checkout;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PaymentStatus: string implements HasLabel, HasColor
{
    case PENDING = "pending";
    case PAID = "paid";
    case FAILED = "failed";
    case REFUNDED = "refunded";

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => __("store.Payment Pending"),
            self::PAID => __("store.Paid"),
            self::FAILED => __("store.Payment Failed"),
            self::REFUNDED => __("store.Refunded"),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => "yellow",
            self::PAID => "green",
            self::FAILED => "red",
            self::REFUNDED => "gray",
        };
    }
}
