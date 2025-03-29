<?php

namespace App\Models;

use App\Enums\Checkout\OrderStatus;
use App\Enums\CommissionStatus;
use App\Services\Currency\CurrencyHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Money\Money;

class AffiliateCommission extends Model
{
    protected $fillable = [
        "affiliate_id",
        "order_id",
        "coupon_id",
        "order_total",
        "commission_rate",
        "commission_amount",
        "status",
        "paid_at",
    ];

    protected $casts = [
        "commission_rate" => "decimal:2",
        "status" => CommissionStatus::class,
        "paid_at" => "datetime",
    ];

    /**
     * Get the affiliate that owns the commission.
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * Get the order that owns the commission.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the coupon that owns the commission.
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the order total as a Money object.
     */
    public function getMoneyOrderTotalAttribute(): Money
    {
        return new Money($this->order_total, CurrencyHelper::defaultCurrency());
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
     * Mark the commission as paid.
     */
    public function markAsPaid(): void
    {
        if (!$this->canBePaid()) {
            return;
        }

        $this->update([
            "status" => CommissionStatus::PAID,
            "paid_at" => now(),
        ]);

        // Update the affiliate's paid commission
        $this->affiliate->increment(
            "paid_commission",
            $this->commission_amount
        );
    }

    /**
     * Determine if the commission can be marked as paid.
     * Only completed orders can have their commissions paid.
     */
    public function canBePaid(): bool
    {
        return $this->status === CommissionStatus::PENDING &&
            $this->order->status === OrderStatus::COMPLETED;
    }

    /**
     * Update commission status based on order status.
     */
    public function updateStatusFromOrder(): void
    {
        if ($this->status !== CommissionStatus::PENDING) {
            return; // Only update pending commissions
        }

        if (
            $this->order->status === OrderStatus::CANCELLED ||
            $this->order->status === OrderStatus::REFUNDED
        ) {
            $this->markAsCanceled();
        }
    }

    /**
     * Mark the commission as canceled.
     */
    public function markAsCanceled(): void
    {
        if ($this->status === CommissionStatus::PAID) {
            return; // Can't cancel a paid commission
        }

        $this->update([
            "status" => CommissionStatus::CANCELED,
        ]);

        // Update the affiliate's total commission
        $this->affiliate->decrement(
            "total_commission",
            $this->commission_amount
        );
    }
}
