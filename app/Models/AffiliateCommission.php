<?php

namespace App\Models;

use App\Enums\Checkout\OrderStatus;
use App\Services\Currency\CurrencyHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Money\Money;

class AffiliateCommission extends Model
{
    protected $fillable = [
        "affiliate_id",
        "coupon_id",
        "affiliate_coupon_id",
        "order_id",
        "order_total",
        "commission_rate",
        "commission_amount",
        "status",
        "paid_at",
    ];

    protected $casts = [
        "commission_rate" => "float",
        "paid_at" => "datetime",
        "status" => OrderStatus::class,
    ];

    /**
     * Get the affiliate associated with this commission.
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * Get the coupon associated with this commission.
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the affiliate coupon associated with this commission.
     */
    public function affiliateCoupon(): BelongsTo
    {
        return $this->belongsTo(AffiliateCoupon::class);
    }

    /**
     * Get the order associated with this commission.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Mark this commission as paid.
     */
    public function markAsPaid(): void
    {
        $this->update([
            "status" => "paid",
            "paid_at" => now(),
        ]);
    }

    /**
     * Get the commission amount as a Money object.
     */
    public function getMoneyCommissionAmountAttribute(): Money
    {
        return new Money(
            $this->commission_amount,
            CurrencyHelper::defaultCurrency()
        );
    }

    /**
     * Get the order total as a Money object.
     */
    public function getMoneyOrderTotalAttribute(): Money
    {
        return new Money($this->order_total, CurrencyHelper::defaultCurrency());
    }
}
