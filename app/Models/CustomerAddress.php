<?php

namespace App\Models;

use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    protected $fillable = [
        "customer_id",
        "phone",
        "address",
        "city",
        "country",
        "postal_code",
        "is_default",
        "is_billing",
        "last_used_at",
        "type",
        "first_name",
        "last_name",
        "state",
    ];

    protected $casts = [
        "is_default" => "boolean",
        "is_billing" => "boolean",
        "last_used_at" => "datetime",
        "type" => AddressType::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
