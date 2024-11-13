<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class FaqSection extends Model
{
    use HasTranslations;
    use HasFactory;

    public array $translatable = ["title", "description"];
    protected $fillable = [
        "title",
        "description",
        "sort_order",
        "is_active",
        "is_product_section",
    ];

    public static function getProductSection(): ?self
    {
        return static::productSection()->first();
    }

    protected static function booted(): void
    {
        // Ensure there's only one product FAQ section
        static::creating(function ($section) {
            if (
                $section->is_product_section &&
                static::where("is_product_section", true)->exists()
            ) {
                throw new \Exception("Only one product FAQ section can exist.");
            }
        });
    }

    public function activeFaqs(): HasMany
    {
        return $this->faqs()->where("is_active", true);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class)->orderBy("sort_order");
    }

    public function scopeProductSection(Builder $query): Builder
    {
        return $query->where("is_product_section", true);
    }

    public function scopeRegularSections(Builder $query): Builder
    {
        return $query->where("is_product_section", false);
    }
}
