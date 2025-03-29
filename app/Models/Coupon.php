<?php

namespace App\Models;

use App\Enums\CouponType;
use App\Services\Currency\CurrencyHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Money\Money;

class Coupon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "code",
        "description",
        "discount_type",
        "discount_amount",
        "minimum_spend",
        "maximum_spend",
        "usage_limit",
        "used_count",
        "is_active",
        "starts_at",
        "expires_at",
        "includes_free_shipping",
        "allowed_shipping_countries",
        "affiliate_id",
        "commission_rate",
    ];

    protected $casts = [
        "discount_type" => CouponType::class,
        "discount_amount" => "decimal:2",
        "minimum_spend" => "decimal:2",
        "maximum_spend" => "decimal:2",
        "is_active" => "boolean",
        "starts_at" => "datetime",
        "expires_at" => "datetime",
        "includes_free_shipping" => "boolean",
        "allowed_shipping_countries" => "json",
        "commission_rate" => "decimal:2",
    ];

    /**
     * Get the affiliate that owns the coupon.
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function isValid(): bool
    {
        if (
            !$this->is_active or
            $this->isExpired() or
            $this->hasReachedUsageLimit()
        ) {
            return false;
        }

        return true;
    }

    public function isExpired(): bool
    {
        if ($this->expires_at && $this->expires_at->isPast()) {
            return true;
        }

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return true;
        }

        return false;
    }

    public function hasReachedUsageLimit(): bool
    {
        if (!$this->usage_limit) {
            return false;
        }

        return $this->used_count >= $this->usage_limit;
    }

    public function incrementUsage(): void
    {
        $this->increment("used_count");
    }

    public function getMoneyMinimumSpendAttribute(): ?Money
    {
        if ($this->minimum_spend === null) {
            return null;
        }
        return new Money(
            $this->minimum_spend,
            CurrencyHelper::defaultCurrency()
        );
    }

    public function getMoneyMaximumSpendAttribute(): ?Money
    {
        if ($this->maximum_spend === null) {
            return null;
        }
        return new Money(
            $this->maximum_spend,
            CurrencyHelper::defaultCurrency()
        );
    }

    public function usage(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, "coupon_usage");
    }

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, "coupon_usage");
    }

    /**
     * Determine if the coupon is an affiliate coupon.
     */
    public function isAffiliateCoupon(): bool
    {
        return $this->affiliate_id !== null;
    }

    /**
     * Scope a query to only include general coupons.
     */
    public function scopeGeneral($query)
    {
        return $query->whereNull("affiliate_id");
    }

    /**
     * Scope a query to only include affiliate coupons.
     */
    public function scopeAffiliate($query)
    {
        return $query->whereNotNull("affiliate_id");
    }
}
