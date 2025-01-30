<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingZone extends Model
{
    protected $fillable = ["name", "countries", "order", "is_active"];

    protected $casts = [
        "countries" => "json",
        "is_active" => "boolean",
        "order" => "integer",
    ];

    public function methods(): HasMany
    {
        return $this->hasMany(ShippingMethod::class)->orderBy("order");
    }

    public function includesCountry(string $countryCode): bool
    {
        return in_array($countryCode, $this->countries);
    }
}
