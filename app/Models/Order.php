<?php

namespace App\Models;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Services\Currency\CurrencyHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Money\Money;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "order_number",
        "customer_id",
        "billing_address_id",
        "shipping_address_id",
        "status",
        "payment_status",
        "shipping_method",
        "subtotal",
        "shipping_cost",
        "total",
        "notes",
        "currency_code",
        "exchange_rate",
    ];

    protected $casts = [
        "status" => OrderStatus::class,
        "payment_status" => PaymentStatus::class,
        "exchange_rate" => "decimal:6",
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(CustomerAddress::class, "billing_address_id");
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(CustomerAddress::class, "shipping_address_id");
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getMoneyTotal(): Money
    {
        return new Money($this->total, CurrencyHelper::defaultCurrency());
    }

    public function getMoneySubtotal(): Money
    {
        return new Money($this->subtotal, CurrencyHelper::defaultCurrency());
    }

    public function getMoneyShippingCost(): Money
    {
        return new Money(
            $this->shipping_cost,
            CurrencyHelper::defaultCurrency()
        );
    }
}
