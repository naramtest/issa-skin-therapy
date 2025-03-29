<?php

namespace App\Enums;

enum CommissionStatus: string
{
    case PENDING = "pending";
    case PAID = "paid";
    case CANCELED = "canceled";

    /**
     * Get the label for the status.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => __("store.Pending"),
            self::PAID => __("store.Paid"),
            self::CANCELED => __("store.Canceled"),
        };
    }

    /**
     * Get the color for the status.
     */
    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => "warning",
            self::PAID => "success",
            self::CANCELED => "danger",
        };
    }
}
