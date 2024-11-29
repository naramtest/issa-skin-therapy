<?php

namespace App\Models;

use App\Enums\CategoryType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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

    //
    public function products(): MorphToMany
    {
        return $this->morphedByMany(Product::class, "model", "categorizables");
    }

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, "model", "categorizables");
    }

    public function determineTitleColumnName(): string
    {
        return "name";
    }

    public function scopeVisible($query)
    {
        return $query->where("is_visible", 1);
    }

    public function scopeProduct($query)
    {
        return $query->where("type", CategoryType::PRODUCT);
    }

    public function scopePost($query)
    {
        return $query->where("type", CategoryType::POST);
    }

    public function byOrder(): self
    {
        return $this->orderBy("order");
    }
}
