<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum SubscriberStatus: string implements HasLabel, HasColor
{
    case PENDING = "Pending";
    case ACTIVE = "Active";
    case UNSUBSCRIBED = "Unsubscribed";

    public function getLabel(): string
    {
        return match ($this) {
            self::PENDING => __("store.Pending"),
            self::ACTIVE => __("store.Active"),
            self::UNSUBSCRIBED => __("store.Unsubscribed"),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::PENDING => "warning",
            self::ACTIVE => "success",
            self::UNSUBSCRIBED => "danger",
        };
    }
}
