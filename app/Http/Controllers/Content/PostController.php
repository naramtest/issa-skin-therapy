<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\Post\PostCacheService;

class PostController extends Controller
{
    public function __construct(
        private readonly PostCacheService $postCacheService
    ) {
    }

    public function index()
    {
        $categories = $this->postCacheService->getCategories();

        return view("storefront.posts.index", ["categories" => $categories]);
    }

    public function show(Post $post)
    {
        //       TODO: cache this using Spatie cache not laravel cache
        $nextPost = Post::select(["id", "title", "slug", "published_at"])
            ->where("id", ">", $post->id)
            ->byDate()
            ->first();
        $pastPost = Post::select(["id", "title", "slug", "published_at"])
            ->where("id", "<", $post->id)
            ->byDate()
            ->first();
        $latestPosts = Post::select([
            "id",
            "title",
            "slug",
            "published_at",
            "excerpt",
        ])
            ->where("id", "!=", $post->id)
            ->byDate()
            ->limit(3)
            ->get();

        return view("storefront.posts.show", [
            "post" => $post,
            "latestPosts" => $latestPosts,
            "nextPost" => $nextPost,
            "pastPost" => $pastPost,
        ]);
    }

    public function preview(int $id)
    {
    }
}
