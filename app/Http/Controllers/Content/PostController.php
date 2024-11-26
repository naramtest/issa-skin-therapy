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
        return view("storefront.posts.show", ["post" => $post]);
    }

    public function preview(int $id)
    {
    }
}
