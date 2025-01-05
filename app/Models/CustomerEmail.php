<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerEmail extends Model
{
    protected $fillable = [
        "customer_id",
        "email",
        "is_verified",
        "is_primary",
        "last_used_at",
    ];

    protected $casts = [
        "is_verified" => "boolean",
        "is_primary" => "boolean",
        "last_used_at" => "datetime",
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
