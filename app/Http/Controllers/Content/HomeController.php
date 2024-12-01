<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductCacheService;

class HomeController extends Controller
{
    public function index(ProductCacheService $productCacheService)
    {
        $bundles = $productCacheService->allBundles();
        $categories = $productCacheService->allProductCategories();
        $featuredProduct = $productCacheService->getFeaturedProduct();
        return view("storefront.home", ['bundles' => $bundles,
            'categories' => $categories, 'featuredProduct' => $featuredProduct]);
    }
}
