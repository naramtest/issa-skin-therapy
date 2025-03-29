<?php

namespace App\Models;

use App\Services\Currency\CurrencyHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Money\Money;

class Affiliate extends Model
{
    protected $fillable = [
        "user_id",
        "phone",
        "slug",
        "about",
        "status",
        "total_commission",
        "paid_commission",
    ];

    protected $casts = [
        "status" => "boolean",
    ];

    /**
     * Get the user that owns the affiliate.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the coupons for the affiliate.
     */
    public function coupons(): HasMany
    {
        return $this->hasMany(Coupon::class);
    }

    /**
     * Get the commission tracks for the affiliate.
     */
    public function commissionTracks(): HasMany
    {
        return $this->hasMany(AffiliateCommission::class);
    }

    /**
     * Get the total commission as a Money object.
     */
    public function getMoneyTotalCommissionAttribute(): Money
    {
        return new Money(
            $this->total_commission,
            CurrencyHelper::defaultCurrency()
        );
    }

    /**
     * Get the paid commission as a Money object.
     */
    public function getMoneyPaidCommissionAttribute(): Money
    {
        return new Money(
            $this->paid_commission,
            CurrencyHelper::defaultCurrency()
        );
    }

    /**
     * Get the unpaid commission as a Money object.
     */
    public function getMoneyUnpaidCommissionAttribute(): Money
    {
        return $this->money_total_commission->subtract(
            $this->money_paid_commission
        );
    }
}
