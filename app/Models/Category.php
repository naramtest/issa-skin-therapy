<?php

namespace App\Models;

use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Cache;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;

    public array $sortable = [
        "order_column_name" => "order",
    ];

    public array $translatable = ["name", "description"];

    protected $casts = [
        "is_visible" => "boolean",
        "type" => CategoryType::class,
    ];

    protected $fillable = [
        "name",
        "slug",
        "description",
        "order",
        "is_visible",
        "type",
    ];

    protected static function booted(): void
    {
        static::created(function () {
            Cache::forget(config("cache-const.categories"));
        });
        static::updated(function () {
            Cache::forget(config("cache-const.categories"));
        });
    }

    //
    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, "model", "categorizables");
    }

    public function determineTitleColumnName(): string
    {
        return "name";
    }

    public function scopeVisible($query)
    {
        return $query->where("is_visible", 1);
    }

    public function scopeProject($query)
    {
        return $query->where("type", CategoryType::PRODUCT);
    }

    public function scopePost($query)
    {
        return $query->where("type", CategoryType::POST);
    }
}
