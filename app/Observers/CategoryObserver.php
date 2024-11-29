<?php

namespace App\Observers;

use App\Enums\CategoryType;
use App\Models\Category;
use App\Services\Post\PostCacheService;
use App\Services\Product\ProductCacheService;

readonly class CategoryObserver
{
    public function __construct(private PostCacheService $postCacheService, private ProductCacheService $productCacheService)
    {
    }

    public function saved(Category $category): void
    {
        if ($category->type === CategoryType::PRODUCT) {
            $this->productCacheService->clearAllCategoriesCache();
        }
        $this->postCacheService->clearCategoriesCache([$category->id]);
    }

    public function deleted(Category $category): void
    {
        if ($category->type === CategoryType::PRODUCT) {
            $this->productCacheService->clearAllCategoriesCache();
        }
        $this->postCacheService->clearCategoriesCache([$category->id]);
    }
}
