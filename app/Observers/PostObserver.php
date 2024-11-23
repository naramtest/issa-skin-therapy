<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\Post\PostImageManagerService;

readonly class PostObserver
{
    //TODO: add cache like the one in productCache observe here and in the bundle
    public function __construct(
        private PostImageManagerService $postImageManagerService
    ) {
    }

    public function creating(Post $post): void
    {
        $this->editBody($post);
    }

    public function editBody(Post $post): void
    {
        $post->body = $this->postImageManagerService->editBody($post);
        $post->edited = true;
        $this->postImageManagerService->cleanupUnusedImages($post);
    }

    public function updating(Post $post): void
    {
        if ($post->isDirty("body")) {
            $this->editBody($post);
        }
    }
}
