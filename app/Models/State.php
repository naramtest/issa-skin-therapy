<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    protected $fillable = [
        "country_id",
        "name",
        "type",
        "state_code",
        "latitude",
        "longitude",
        "is_active",
    ];

    protected $casts = [
        "latitude" => "decimal:8",
        "longitude" => "decimal:8",
        "is_active" => "boolean",
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function scopeActive($query)
    {
        return $query->where("is_active", true);
    }
}
