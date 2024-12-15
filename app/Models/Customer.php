<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    protected $fillable = [
        "user_id",
        "name", // username or display name
        "first_name",
        "last_name",
        "email",
        "orders_count",
        "last_order_at",
        "total_spent",
        "is_registered",
    ];

    protected $casts = [
        "last_order_at" => "datetime",
        "total_spent" => "decimal:2",
        "is_registered" => "boolean",
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->name)) {
                $customer->name = strtolower(
                    str_replace(
                        " ",
                        "",
                        $customer->first_name . $customer->last_name
                    )
                );
            }
        });
    }

    //    public function orders()
    //    {
    //        return $this->hasMany(Order::class);
    //    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function defaultAddress(): HasOne
    {
        return $this->hasOne(CustomerAddress::class)->where("is_default", true);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
