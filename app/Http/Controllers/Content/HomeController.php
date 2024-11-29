<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductCacheService;

class HomeController extends Controller
{
    public function index(ProductCacheService $productCacheService)
    {
        //TODO : add Cache here
        $bundles = $productCacheService->allBundles();
        $categories = $productCacheService->allProductCategories();
        return view("storefront.home", ['bundles' => $bundles,
            'categories' => $categories]);
    }
}
