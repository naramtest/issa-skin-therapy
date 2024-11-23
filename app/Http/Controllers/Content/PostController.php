<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return view("storefront.posts.index");
    }

    public function show(Post $post)
    {
        return view("storefront.posts.show", ["post" => $post]);
    }
}
