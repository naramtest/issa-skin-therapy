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
    const CACHE_KEY_ALL_BUNDLES = "all_bundles";
    const CACHE_KEY_FEATURED_PRODUCT = "all_featured_product";
    const CACHE_KEY_ALL_CATEGORIES = "all_categories";
    const COLUMNS = [
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
        "regular_price",
        "sale_price",
        "quantity",
        "stock_status",
    ];

    /**
     * Get product FAQs (cached)
     */
    public function allProducts(): Collection
    {
        $query = Product::select(self::COLUMNS)
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
     * Get Featured Product (cached)
     */
    public function getFeaturedProduct(): Product
    {
        $query = Product::select(array_merge(self::COLUMNS, ['short_description']))
            ->where('is_featured', true)
            ->with([
                "media",
            ])
            ->first();
        if (App::isLocal()) {
            return $query;
        }
        return Cache::remember(
            self::CACHE_KEY_ALL_PRODUCTS,
            self::CACHE_DURATION,
            fn() => $query ?? Collection::make()
        );
    }

    public function allBundles(): Collection
    {

        $query = App\Models\Bundle::select(array_merge(self::COLUMNS, ['subtitle']))->get();

        if (App::isLocal()) {
            return $query;
        }

        return Cache::remember(
            self::CACHE_KEY_ALL_BUNDLES,
            self::CACHE_DURATION,
            fn() => $query ?? Collection::make()
        );
    }

    public function allProductCategories(): Collection
    {

        $query = App\Models\Category::select(['slug', 'name', 'id', 'order', 'type', 'is_visible'])->byOrder()->visible()->product()->get();

        if (App::isLocal()) {
            return $query;
        }

        return Cache::remember(
            self::CACHE_KEY_ALL_CATEGORIES,
            self::CACHE_DURATION,
            fn() => $query ?? Collection::make()
        );
    }


    public function clearAllProductCache(): void
    {
        Cache::forget(self::CACHE_KEY_ALL_PRODUCTS);
    }

    public function clearAllBundlesCache(): void
    {
        Cache::forget(self::CACHE_KEY_ALL_BUNDLES);
    }

    public function clearAllCategoriesCache(): void
    {
        Cache::forget(self::CACHE_KEY_ALL_CATEGORIES);
    }

    public function clearFeaturedProductCache(): void
    {
        Cache::forget(self::CACHE_KEY_FEATURED_PRODUCT);
    }
}
