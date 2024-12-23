<?php

namespace App\Enums\Checkout;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel, HasColor
{
    case PENDING = "pending";
    case PROCESSING = "processing";
    case COMPLETED = "completed";
    case CANCELLED = "cancelled";
    case REFUNDED = "refunded";
    case ON_HOLD = "on_hold";

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => __("store.Pending"),
            self::PROCESSING => __("store.Processing"),
            self::COMPLETED => __("store.Completed"),
            self::CANCELLED => __("store.Cancelled"),
            self::REFUNDED => __("store.Refunded"),
            self::ON_HOLD => __("store.On Hold"),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => "gray",
            self::PROCESSING => "blue",
            self::COMPLETED => "green",
            self::CANCELLED => "red",
            self::REFUNDED => "yellow",
            self::ON_HOLD => "orange",
        };
    }
}
