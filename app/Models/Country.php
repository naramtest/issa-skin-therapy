<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = [
        "name",
        "iso2",
        "iso3",
        "native",
        "currency",
        "currency_name",
        "currency_symbol",
        "phone_code",
        "region",
        "subregion",
        "latitude",
        "longitude",
        "emoji",
        "is_active",
    ];

    protected $casts = [
        "latitude" => "decimal:8",
        "longitude" => "decimal:8",
        "is_active" => "boolean",
    ];

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function scopeActive($query)
    {
        return $query->where("is_active", true);
    }
}
