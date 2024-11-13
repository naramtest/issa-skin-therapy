<?php

namespace App\Services\Faq;

use App\Models\FaqSection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class FaqService
{
    /**
     * Cache duration in seconds (1 week)
     */
    const CACHE_DURATION = 2628000;

    /**
     * Cache keys
     */
    const CACHE_KEY_PRODUCT_FAQS = "product_faqs";
    const CACHE_KEY_REGULAR_FAQS = "regular_faqs";
    const CACHE_KEY_ALL_FAQS = "all_faqs";

    /**
     * Get product FAQs (cached)
     */
    public function getProductFaqs(): Collection
    {
        return Cache::remember(
            self::CACHE_KEY_PRODUCT_FAQS,
            self::CACHE_DURATION,
            function () {
                return FaqSection::productSection()
                    ->where("is_active", true)
                    ->with([
                        "activeFaqs" => function ($query) {
                            $query->orderBy("sort_order");
                        },
                    ])
                    ->first()?->activeFaqs ?? Collection::make();
            }
        );
    }

    /**
     * Get regular FAQs (cached)
     */
    public function getRegularFaqs(): Collection
    {
        return Cache::remember(
            self::CACHE_KEY_REGULAR_FAQS,
            self::CACHE_DURATION,
            function () {
                return FaqSection::regularSections()
                    ->where("is_active", true)
                    ->with([
                        "activeFaqs" => function ($query) {
                            $query->orderBy("sort_order");
                        },
                    ])
                    ->orderBy("sort_order")
                    ->get();
            }
        );
    }

    /**
     * Clear all FAQ caches
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY_PRODUCT_FAQS);
        Cache::forget(self::CACHE_KEY_REGULAR_FAQS);
        Cache::forget(self::CACHE_KEY_ALL_FAQS);
    }
}
