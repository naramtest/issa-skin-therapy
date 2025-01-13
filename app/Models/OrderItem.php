<?php

namespace App\Models;

use App\Services\Currency\CurrencyHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Money\Money;

class OrderItem extends Model
{
    protected $fillable = [
        "order_id",
        "purchasable_id",
        "purchasable_type",
        "quantity",
        "unit_price",
        "subtotal",
        "options",
    ];

    protected $casts = [
        "quantity" => "integer",
        "options" => "json",
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getMoneyUnitPriceAttribute(): Money
    {
        return new Money($this->unit_price, CurrencyHelper::defaultCurrency());
    }

    public function getMoneySubtotal(): Money
    {
        return new Money($this->subtotal, CurrencyHelper::defaultCurrency());
    }
}
