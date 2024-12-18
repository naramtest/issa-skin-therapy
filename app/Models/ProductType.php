<?php

namespace App\Models;

use App\Services\Product\ProductCacheService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class ProductType extends Model
{
    use HasTranslations;

    public array $translatable = ["name"];
    protected $fillable = ["name", "slug"];

    protected static function booted(): void
    {
        static::saved(function () {
            app(ProductCacheService::class)->clearAllTypesCache();
        });
        static::deleted(function () {
            app(ProductCacheService::class)->clearAllTypesCache();
        });
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }
}
