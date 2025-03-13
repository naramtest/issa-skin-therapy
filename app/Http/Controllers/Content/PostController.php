<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\Post\PostCacheService;
use App\Services\SEO\Schema;
use App\Traits\Seo\HasPageSeo;

class PostController extends Controller
{
    use HasPageSeo;

    public function __construct(
        private readonly PostCacheService $postCacheService
    ) {
    }

    public function index()
    {
        $categories = $this->postCacheService->getCategories();
        $posts = $this->postCacheService->getAllPosts(5);
        return view("storefront.posts.index", [
            "categories" => $categories,
            "graph" => Schema::getSchema("blog", data: $posts),
            "seo" => self::seoData(
                title: getPageTitle(__("store.Blog")),
                description: __(
                    "store.Explore our articles and insights on skincare, beauty tips, and product recommendations"
                ),
                image: "storage/test/hero1.webp",
                tags: ["blog", "skincare", "beauty", "tips", "skincare advice"],
                section: "Blog"
            ),
        ]);
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
            "graph" => Schema::getSchema("post", data: $post),
        ]);
    }

    public function preview(int $id)
    {
    }
}
