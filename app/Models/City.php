<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $fillable = [
        "state_id",
        "name",
        "latitude",
        "longitude",
        "is_active",
    ];

    protected $casts = [
        "latitude" => "decimal:8",
        "longitude" => "decimal:8",
        "is_active" => "boolean",
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function scopeActive($query)
    {
        return $query->where("is_active", true);
    }
}
