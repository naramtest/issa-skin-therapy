<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    protected $fillable = [
        "name",
        "countries",
        "is_all_countries",
        "order",
        "is_active",
    ];

    protected $casts = [
        "countries" => "json",
        "is_all_countries" => "boolean",
        "is_active" => "boolean",
        "order" => "integer",
    ];

    public function methods(): HasMany
    {
        return $this->hasMany(ShippingMethod::class)->orderBy("order");
    }

    public function includesCountry(string $countryCode): bool
    {
        if ($this->is_all_countries) {
            return true;
        }

        return in_array($countryCode, $this->countries ?? []);
    }

    // Return zones in correct priority order (specific countries before catch-all)
    public function scopeByPriority($query)
    {
        return $query->orderBy("is_all_countries")->orderBy("order");
    }
}
