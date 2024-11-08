<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum StockStatus: string implements HasLabel, HasColor
{
    case IN_STOCK = "in_stock";
    case OUT_OF_STOCK = "out_of_stock";
    case LOW_STOCK = "low_stock";
    case BACKORDER = "backorder";
    case PREORDER = "preorder";
    case DISCONTINUED = "discontinued";

    /**
     * Get the display name for the stock status
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::IN_STOCK => __("dashboard.In Stock"),
            self::OUT_OF_STOCK => __("dashboard.Out of Stock"),
            self::LOW_STOCK => __("dashboard.Low Stock"),
            self::BACKORDER => __("dashboard.Available for Backorder"),
            self::PREORDER => __("dashboard.Available for Pre-order"),
            self::DISCONTINUED => __("dashboard.Discontinued"),
        };
    }

    /**
     * Get the color for the stock status (useful for badges/labels)
     */
    public function getColor(): string
    {
        return match ($this) {
            self::IN_STOCK => "green",
            self::OUT_OF_STOCK => "red",
            self::LOW_STOCK => "yellow",
            self::BACKORDER => "orange",
            self::PREORDER => "blue",
            self::DISCONTINUED => "gray",
        };
    }

    /**
     * Check if the product is available for purchase
     */
    public function isAvailableForPurchase(): bool
    {
        return match ($this) {
            self::IN_STOCK,
            self::LOW_STOCK,
            self::BACKORDER,
            self::PREORDER
                => true,
            self::OUT_OF_STOCK, self::DISCONTINUED => false,
        };
    }

    /**
     * Get Tailwind CSS classes for the status badge
     */
    public function getTailwindClasses(): string
    {
        return match ($this) {
            self::IN_STOCK => "bg-green-100 text-green-800",
            self::OUT_OF_STOCK => "bg-red-100 text-red-800",
            self::LOW_STOCK => "bg-yellow-100 text-yellow-800",
            self::BACKORDER => "bg-orange-100 text-orange-800",
            self::PREORDER => "bg-blue-100 text-blue-800",
            self::DISCONTINUED => "bg-gray-100 text-gray-800",
        };
    }

    /**
     * Get status description for customers
     */
    public function getCustomerMessage(): string
    {
        return match ($this) {
            self::IN_STOCK => "Ready to ship",
            self::OUT_OF_STOCK => "Currently unavailable",
            self::LOW_STOCK => "Only few items left",
            self::BACKORDER => "Available on backorder - ships in 2-3 weeks",
            self::PREORDER => "Available for pre-order",
            self::DISCONTINUED => "This product has been discontinued",
        };
    }

    /**
     * Get inventory action recommendations for admin
     */
    public function getAdminRecommendation(): string
    {
        return match ($this) {
            self::IN_STOCK => "Stock levels are good",
            self::OUT_OF_STOCK => "Restock required immediately",
            self::LOW_STOCK => "Consider restocking soon",
            self::BACKORDER => "Monitor backorder fulfillment timeline",
            self::PREORDER => "Ensure stock arrives before pre-order date",
            self::DISCONTINUED => "Consider removing from active listings",
        };
    }

    /**
     * Determine if status requires admin attention
     */
    public function requiresAttention(): bool
    {
        return in_array($this, [self::OUT_OF_STOCK, self::LOW_STOCK]);
    }
}
