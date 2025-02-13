<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingOrder extends Model
{
    protected $fillable = [
        "order_id",
        "carrier",
        "tracking_number",
        "tracking_url",
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
