<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Affiliate extends Model
{
    protected $fillable = [
        "name",
        "email",
        "phone",
        "type",
        "notes",
        "user_id",
        "is_active",
    ];

    protected $casts = [
        "is_active" => "boolean",
    ];

    /**
     * Get all coupons associated with this affiliate.
     */
    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, "affiliate_coupons")
            ->withPivot([
                "commission_rate",
                "is_active",
                "starts_at",
                "expires_at",
            ])
            ->withTimestamps();
    }

    /**
     * Get all affiliate_coupons for this affiliate.
     */
    public function affiliateCoupons(): HasMany
    {
        return $this->hasMany(AffiliateCoupon::class);
    }

    /**
     * Get the total pending commission amount.
     */
    public function getPendingCommissionAttribute(): int
    {
        return $this->commissions()
            ->where("status", "pending")
            ->sum("commission_amount");
    }

    /**
     * Get all commissions for this affiliate.
     */
    public function commissions(): HasMany
    {
        return $this->hasMany(AffiliateCommission::class);
    }

    /**
     * Get the total paid commission amount.
     */
    public function getPaidCommissionAttribute(): int
    {
        return $this->commissions()
            ->where("status", "paid")
            ->sum("commission_amount");
    }
}
