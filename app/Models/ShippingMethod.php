<?php

namespace App\Models;

use App\Enums\Checkout\ShippingMethodType;
use App\Services\Currency\CurrencyHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Money\Money;

class ShippingMethod extends Model
{
    protected $fillable = [
        "shipping_zone_id",
        "method_type",
        "title",
        "cost",
        "settings",
        "is_active",
        "order",
    ];

    protected $casts = [
        "method_type" => ShippingMethodType::class,
        "settings" => "json",
        "is_active" => "boolean",
        "order" => "integer",
    ];

    public function zone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class, "shipping_zone_id");
    }

    public function meetsMinimumOrderRequirement(Money $orderSubtotal): bool
    {
        if (!$this->hasMinimumOrderRequirement()) {
            return true;
        }

        return $orderSubtotal->greaterThanOrEqual(
            $this->getMinimumOrderAmount()
        );
    }

    public function hasMinimumOrderRequirement(): bool
    {
        return !empty($this->settings["minimum_order_amount"]);
    }

    public function getMinimumOrderAmount(): Money
    {
        return new Money(
            $this->settings["minimum_order_amount"] ?? 0,
            CurrencyHelper::defaultCurrency()
        );
    }

    public function calculateCost(int $itemCount = 1): Money
    {
        $baseCost = $this->getCostMoney();

        if ($this->getCalculationType() === "per_item") {
            return $baseCost->multiply($itemCount);
        }

        return $baseCost;
    }

    public function getCostMoney(): Money
    {
        return new Money($this->cost ?? 0, CurrencyHelper::defaultCurrency());
    }

    public function getCalculationType(): string
    {
        return $this->settings["calculation_type"] ?? "per_order";
    }
}
