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
    case FAILED = "failed";

    //    case DRAFT = "draft";

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => __("store.Pending"),
            self::PROCESSING => __("store.Processing"),
            self::COMPLETED => __("store.Completed"),
            self::CANCELLED => __("store.Cancelled"),
            self::REFUNDED => __("store.Refunded"),
            self::ON_HOLD => __("store.On Hold"),
            self::FAILED => __(
                "store.Failed"
            ), //            self::DRAFT => __("dashboard.Draft"),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            //            self::PENDING, self::DRAFT => "gray",
            self::PENDING => "gray",
            self::PROCESSING => "info", // Changed from blue
            self::COMPLETED => "success", // Changed from green
            self::CANCELLED, self::FAILED => "danger", // Changed from red
            self::REFUNDED => "warning", // Changed from yellow
            self::ON_HOLD => "orange", // Changed from orange
        };
    }
}
