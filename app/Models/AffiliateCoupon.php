<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AffiliateCoupon extends Model
{
    protected $fillable = [
        "affiliate_id",
        "coupon_id",
        "commission_rate",
        "is_active",
        "starts_at",
        "expires_at",
    ];

    protected $casts = [
        "is_active" => "boolean",
        "commission_rate" => "float",
        "starts_at" => "date",
        "expires_at" => "date",
    ];

    /**
     * Get the affiliate that owns this coupon.
     */
    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    /**
     * Get the coupon associated with this affiliate coupon.
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get all usage records for this affiliate coupon.
     */
    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    /**
     * Get all commissions generated from this affiliate coupon.
     */
    public function commissions(): HasMany
    {
        return $this->hasMany(AffiliateCommission::class);
    }

    /**
     * Check if this affiliate coupon is currently valid.
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->expires_at && $now->gt($this->expires_at)) {
            return false;
        }

        // Also check if the base coupon is valid
        return $this->coupon->isValid();
    }
}
