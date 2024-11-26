<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\Post\PostCacheService;

readonly class CategoryObserver
{
    public function __construct(private PostCacheService $postCacheService)
    {
    }

    public function saved(Category $category): void
    {
        $this->postCacheService->clearCategoriesCache([$category->id]);
    }

    public function deleted(Category $category): void
    {
        $this->postCacheService->clearCategoriesCache([$category->id]);
    }
}
