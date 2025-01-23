<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingOrder extends Model
{
    protected $fillable = [
        "order_id",
        "carrier",
        "service_code",
        "tracking_number",
        "label_url",
        "shipping_label_data",
        "carrier_response",
        "weight",
        "length",
        "width",
        "height",
        "status",
        "shipped_at",
        "delivered_at",
    ];

    protected $casts = [
        "carrier_response" => "array",
        "shipped_at" => "datetime",
        "delivered_at" => "datetime",
        "weight" => "decimal:3",
        "length" => "decimal:2",
        "width" => "decimal:2",
        "height" => "decimal:2",
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getTrackingUrl(): ?string
    {
        return match ($this->carrier) {
            "dhl"
                => "https://www.dhl.com/en/express/tracking.html?AWB={$this->tracking_number}",
            default => null,
        };
    }
}
