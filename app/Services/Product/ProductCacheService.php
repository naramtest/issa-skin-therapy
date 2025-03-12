<?php

namespace App\Services\Product;

use App;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
    const CACHE_KEY_PAGINATE_PRODUCTS = "paginate_products";
    const CACHE_KEY_ALL_BUNDLES = "all_bundles";
    const CACHE_KEY_FEATURED_PRODUCT = "all_featured_product";
    const CACHE_KEY_ALL_CATEGORIES = "all_categories";
    const CACHE_KEY_ALL_TYPES = "all_types";
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
        "track_quantity",
        "allow_backorders",
    ];

    public function getPaginatedProduct(int $perPage = 9): LengthAwarePaginator
    {
        try {
            $page = request()->get("page", 1);
            $cacheKey = "products.page.$page.$perPage";

            $tags = ["products"];

            return Cache::tags($tags)->remember(
                $cacheKey,
                self::CACHE_KEY_PAGINATE_PRODUCTS,
                fn() => $this->queryPosts()->paginate($perPage)
            );
        } catch (\Exception | NotFoundExceptionInterface | ContainerExceptionInterface $e) {
            return $this->queryPosts()->paginate($perPage);
        }
    }

    private function queryPosts()
    {
        return Product::query()
            ->select(self::COLUMNS)
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
            ]);
    }

    /**
     * Get product FAQs (cached)
     */
    public function allProducts(): Collection
    {
        $query = $this->queryPosts()->get();
        //        if (App::isLocal()) {
        //            return $query;
        //        }
        return Cache::remember(
            self::CACHE_KEY_ALL_PRODUCTS,
            self::CACHE_DURATION,
            fn() => $query ?? Collection::make()
        );
    }

    /**
     * Get Featured Product (cached)
     */
    public function getFeaturedProduct(): ?Product
    {
        $query = Product::select(
            array_merge(self::COLUMNS, ["short_description"])
        )
            ->published()
            ->where("is_featured", true)
            ->with(["media"])
            ->first();
        //        if (App::isLocal()) {
        //            return $query;
        //        }
        return Cache::remember(
            self::CACHE_KEY_FEATURED_PRODUCT,
            self::CACHE_DURATION,
            fn() => $query ?? Collection::make()
        );
    }

    public function allBundles(): Collection
    {
        $query = App\Models\Bundle::select(
            array_merge(self::COLUMNS, ["subtitle"])
        )->get();

        if (App::isLocal()) {
            return $query;
        }

        return Cache::remember(
            self::CACHE_KEY_ALL_BUNDLES,
            self::CACHE_DURATION,
            fn() => $query ?? Collection::make()
        );
    }

    public function allProductCategories(
        int $productsPerCategory = 2
    ): Collection {
        $query = App\Models\Category::select([
            "slug",
            "name",
            "id",
            "order",
            "type",
            "is_visible",
        ])
            ->byOrder()
            ->visible()
            ->product()
            ->with([
                "products" => function ($query) use ($productsPerCategory) {
                    $query
                        ->select(self::COLUMNS)
                        ->published()
                        ->byOrder()
                        ->with(["media"])
                        ->limit($productsPerCategory);
                },
            ])
            ->get();

        if (App::isLocal()) {
            return $query;
        }

        return Cache::remember(
            self::CACHE_KEY_ALL_CATEGORIES,
            self::CACHE_DURATION,
            fn() => $query ?? Collection::make()
        );
    }

    public function allProductTypes(int $productsPerCategory = 2): Collection
    {
        $query = App\Models\ProductType::select(["slug", "name", "id"])->get();

        if (App::isLocal()) {
            return $query;
        }

        return Cache::remember(
            self::CACHE_KEY_ALL_TYPES,
            self::CACHE_DURATION,
            fn() => $query ?? Collection::make()
        );
    }

    public function clearAllProductCache(): void
    {
        Cache::forget(self::CACHE_KEY_ALL_PRODUCTS);
        Cache::tags(["posts"])->flush();
    }

    public function clearAllBundlesCache(): void
    {
        Cache::forget(self::CACHE_KEY_ALL_BUNDLES);
    }

    public function clearAllCategoriesCache(): void
    {
        Cache::forget(self::CACHE_KEY_ALL_CATEGORIES);
    }

    public function clearAllTypesCache(): void
    {
        Cache::forget(self::CACHE_KEY_ALL_TYPES);
    }

    public function clearFeaturedProductCache(): void
    {
        Cache::forget(self::CACHE_KEY_FEATURED_PRODUCT);
    }
}
