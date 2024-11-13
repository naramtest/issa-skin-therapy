<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasTranslations;
    use HasFactory;

    public array $translatable = ["question", "answer"];
    protected $fillable = [
        "faq_section_id",
        "question",
        "answer",
        "sort_order",
        "is_active",
    ];
    protected $casts = [
        "is_active" => "boolean",
        "sort_order" => "integer",
    ];

    protected static function booted(): void
    {
        // Auto-set sort order if not provided
        static::creating(function ($faq) {
            if (!$faq->sort_order) {
                $faq->sort_order =
                    static::where("faq_section_id", $faq->faq_section_id)->max(
                        "sort_order"
                    ) + 1;
            }
        });

        // Ensure the FAQ's section exists
        static::saving(function ($faq) {
            if (!FaqSection::find($faq->faq_section_id)) {
                throw new \Exception(
                    "The specified FAQ section does not exist."
                );
            }
        });
    }

    /**
     * Get the section that owns the FAQ.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(FaqSection::class, "faq_section_id");
    }

    /**
     * Scope a query to only include active FAQs.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where("is_active", true);
    }
}
