<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\Post\PostCacheService;
use App\Services\Post\PostImageManagerService;

readonly class PostObserver
{
    public function __construct(
        private PostImageManagerService $postImageManagerService,
        private PostCacheService $postCacheService
    ) {
    }

    public function saved(Post $post): void
    {
        $this->postCacheService->clearPostsCache();
    }

    public function deleted(Post $post): void
    {
        $this->postCacheService->clearPostsCache();
    }

    public function creating(Post $post): void
    {
        $this->processBody($post);
    }

    public function processBody(Post $post): void
    {
        $post->body = $this->postImageManagerService->editBody($post);
        $post->edited = true;
        $this->postImageManagerService->cleanupUnusedImages($post);
    }

    public function updating(Post $post): void
    {
        if ($post->isDirty("body")) {
            $this->processBody($post);
        }
    }
}
