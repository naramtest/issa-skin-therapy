<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductCacheService;

class ShopController extends Controller
{
    public function index(ProductCacheService $productCacheService)
    {
        $bundles = $productCacheService->allBundles();
        $products = $productCacheService->getPaginatedProduct(12);
        return view("storefront.shop", [
            "bundles" => $bundles,
            "products" => $products,
        ]);
    }
}
