<?php

namespace App\Models;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Services\Currency\CurrencyHelper;
use Finller\Invoice\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
        "email",

        //payment columns
        "payment_provider",
        "payment_intent_id",
        "payment_method_details",
        "payment_authorized_at",
        "payment_captured_at",
        "payment_refunded_at",
    ];

    protected $casts = [
        "payment_status" => PaymentStatus::class,
        "exchange_rate" => "decimal:6",
        "payment_method_details" => "json",
        "payment_authorized_at" => "datetime",
        "payment_captured_at" => "datetime",
        "payment_refunded_at" => "datetime",
        "status" => OrderStatus::class,
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

    public function getMoneyShippingCostAttribute(): Money
    {
        return new Money(
            $this->shipping_cost,
            CurrencyHelper::defaultCurrency()
        );
    }

    public function markPaymentAuthorized(): void
    {
        $this->update([
            "payment_status" => PaymentStatus::PAID,
            "payment_authorized_at" => now(),
            "status" => OrderStatus::PROCESSING,
        ]);
    }

    public function markPaymentCaptured(): void
    {
        $this->update([
            "payment_captured_at" => now(),
        ]);
    }

    public function markPaymentFailed(): void
    {
        $this->update([
            "payment_status" => PaymentStatus::FAILED,
            "status" => OrderStatus::CANCELLED,
        ]);
    }

    public function markPaymentRefunded(): void
    {
        $this->update([
            "payment_status" => PaymentStatus::REFUNDED,
            "payment_refunded_at" => now(),
            "status" => OrderStatus::REFUNDED,
        ]);
    }

    public function invoices(): MorphMany
    {
        return $this->morphMany(Invoice::class, "invoiceable");
    }

    public function couponUsage(): HasOne
    {
        return $this->hasOne(CouponUsage::class);
    }

    public function shippingOrder(): HasOne
    {
        return $this->hasOne(ShippingOrder::class);
    }
}
