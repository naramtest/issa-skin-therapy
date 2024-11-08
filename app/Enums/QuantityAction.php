<?php

namespace App\Enums;

enum QuantityAction: string
{
    case ADD = "add";
    case SUBTRACT = "subtract";
    case SET = "set";

    /**
     * Get the description for the action
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::ADD => "Added to stock",
            self::SUBTRACT => "Removed from stock",
            self::SET => "Stock level set to",
        };
    }

    /**
     * Get sign for the action (for display purposes)
     */
    public function getSign(): string
    {
        return match ($this) {
            self::ADD => "+",
            self::SUBTRACT => "-",
            self::SET => "=",
        };
    }
}
