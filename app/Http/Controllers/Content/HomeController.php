<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Export\OrderExportService;
use App\Services\Post\PostCacheService;
use App\Services\Product\ProductCacheService;

class HomeController extends Controller
{
    public function index(
        ProductCacheService $productCacheService,
        PostCacheService $postCacheService
    ) {
        return app(OrderExportService::class)->exportToDHL(
            Order::all()->take(3)
        );
        //        $bundles = $productCacheService->allBundles();
        //        $categories = $productCacheService->allProductCategories();
        //        $featuredProduct = $productCacheService->getFeaturedProduct();
        //        $posts = $postCacheService->getHomePost();
        //        return view("storefront.home", [
        //            "bundles" => $bundles,
        //            "categories" => $categories,
        //            "featuredProduct" => $featuredProduct,
        //            "posts" => $posts,
        //        ]);
    }
}
