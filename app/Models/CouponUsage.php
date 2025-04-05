<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CouponUsage extends Model
{
    protected $fillable = [
        "coupon_id",
        "order_id",
        "customer_id",
        "discount_amount",
        "affiliate_coupon_id",
        "used_at",
    ];

    protected $casts = [
        "used_at" => "datetime",
        "discount_amount" => "decimal:2",
    ];

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
