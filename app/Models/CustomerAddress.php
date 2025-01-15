<?php

namespace App\Models;

use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAddress extends Model
{
    protected $fillable = [
        "customer_id",
        "first_name",
        "last_name",
        "phone",
        "address",
        "city",
        "state",
        "country",
        "postal_code",
        "area",
        "building",
        "flat",
        "type",
        "is_default",
        "is_billing",
        "last_used_at",
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

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFullAddressAttribute(): string
    {
        $parts = [
            $this->address,
            $this->area ? "Area: {$this->area}" : null,
            $this->building ? "Building: {$this->building}" : null,
            $this->flat ? "Flat: {$this->flat}" : null,
            $this->city,
            $this->state,
            $this->country,
            $this->postal_code,
        ];

        return collect($parts)->filter()->join(", ");
    }
}
