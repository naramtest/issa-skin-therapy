<?php

namespace App\Services\Product;

use App;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ProductCacheService
{
    /**
     * Cache duration in seconds (1 week)
     */
    const CACHE_DURATION = 2628000;

    /**
     * Cache keys
     */

    const CACHE_KEY_ALL_PRODUCTS = "all_products";

    /**
     * Get product FAQs (cached)
     */
    public function all(): Collection
    {
        $query = Product::select([
            "id",
            "name",
            "order",
            "status",
            "published_at",
            "created_at",
            "updated_at",
            "deleted_at",
            "is_sale_scheduled",
            "sale_starts_at",
            "sale_ends_at",
            "slug",
            "description",
            "short_description",
            "regular_price",
            "sale_price",
            "quantity",
            "stock_status",
        ])
            ->published()
            ->byOrder()
            ->with([
                "media",
                "categories" => function ($query) {
                    $query->select(
                        "categories.id",
                        "categories.name",
                        "categories.slug"
                    );
                },
                "types" => function ($query) {
                    $query->select(
                        "product_types.id",
                        "product_types.name",
                        "product_types.slug"
                    );
                },
            ])
            ->get();
        if (App::isLocal()) {
            return $query;
        }
        return Cache::remember(
            self::CACHE_KEY_ALL_PRODUCTS,
            self::CACHE_DURATION,
            fn() => $query ?? Collection::make()
        );
    }

    /**
     * Clear all FAQ caches
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY_ALL_PRODUCTS);
    }
}
