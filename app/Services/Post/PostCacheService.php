<?php

namespace App\Services\Post;

use App;
use App\Enums\CategoryType;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PostCacheService
{
    //TODO: test this if its work (add and removing the cache)
    //TODO: switch to spatie cache / for posts , faq ... etc / to know what to do ask claude how to cache a pagination
    /**
     * Cache duration in seconds (1 week)
     */
    const COLUMNS = [
        "id",
        "title",
        "body",
        "meta_title",
        "slug",
        "meta_description",
        "status",
        "published_at",
        "is_featured",
        "excerpt",
    ];

    private const POST_CACHE_TTL = 30 * 24 * 3600;
    private const CATEGORY_CACHE_TTL = 30 * 24 * 3600; // 30 days in seconds
    private const CACHE_KEY_HOME_PRODUCTS = 'home_products'; // 30 days in seconds

    public function getHomePost()
    {
        $query = Post::select(self::COLUMNS)
            ->published()
            ->byDate()
            ->with([
                "media",
                "categories" => function ($query) {
                    $query->select(
                        "categories.id",
                        "categories.name",
                        "categories.slug"
                    );
                },

            ])
            ->limit(3)
            ->get();
        if (App::isLocal()) {
            return $query;
        }
        return Cache::remember(
            self::CACHE_KEY_HOME_PRODUCTS,
            self::POST_CACHE_TTL,
            fn() => $query ?? Collection::make()
        );
    }

    public function getPaginatedPosts(
        ?array $categoryIds = null,
        int    $perPage = 12
    ): LengthAwarePaginator
    {
        try {
            $page = request()->get("page", 1);
            $cacheKey = $this->generatePostsCacheKey(
                $categoryIds,
                $page,
                $perPage
            );

            $tags = ["posts"];

            if ($categoryIds) {
                foreach ($categoryIds as $categoryId) {
                    $tags[] = "category-{$categoryId}";
                }
            }

            return Cache::tags($tags)->remember(
                $cacheKey,
                self::POST_CACHE_TTL,
                fn() => $this->queryPosts($categoryIds, $perPage)
            );
        } catch (\Exception|NotFoundExceptionInterface|ContainerExceptionInterface $e) {
            return $this->queryPosts($categoryIds, $perPage);
        }
    }

    private function generatePostsCacheKey(
        ?array $categoryIds,
        int    $page,
        int    $perPage
    ): string
    {
        $key = "posts.page.$page.$perPage";

        if ($categoryIds) {
            sort($categoryIds); // Ensure consistent order for cache key
            $key .= ".categories-" . implode("-", $categoryIds);
        }

        return $key;
    }

    private function queryPosts(
        ?array $categoryIds,
        int    $perPage
    ): LengthAwarePaginator
    {
        $query = Post::query()
            ->select(self::COLUMNS)
            ->with([
                "media",
                "categories" => function ($query) {
                    $query->select(
                        "categories.id",
                        "categories.name",
                        "categories.slug"
                    );
                },
            ])
            ->byDate()
            ->latest();

        if ($categoryIds) {
            $query->whereHas("categories", function ($query) use (
                $categoryIds
            ) {
                $query->whereIn("categories.id", $categoryIds);
            });
        }

        return $query->paginate($perPage);
    }

    public function getCategories(): Collection
    {
        return Cache::tags(["categories"])->remember(
            "blog.categories",
            self::CATEGORY_CACHE_TTL,
            fn() => Category::select(["id", "name", "slug"])
                ->where("type", CategoryType::POST)
                ->orderBy("order")
                ->get()
        );
    }

    public function clearCategoriesCache(?array $categoryIds = null): void
    {
        if ($categoryIds) {
            foreach ($categoryIds as $categoryId) {
                Cache::tags(["category-{$categoryId}"])->flush();
            }
        }

        Cache::tags(["categories"])->flush();
    }

    public function clearPostsCache(): void
    {
        Cache::tags(["posts"])->flush();
    }
}
