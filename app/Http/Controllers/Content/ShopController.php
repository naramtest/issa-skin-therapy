<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductCacheService;
use App\Services\SEO\SchemaServices\ShopPageSchemaService;

class ShopController extends Controller
{
    public function index(
        ProductCacheService $productCacheService,
        ShopPageSchemaService $shopPageSchemaService
    ) {
        $bundles = $productCacheService->allBundles();
        $products = $productCacheService->getPaginatedProduct(12);

        return view("storefront.shop", [
            "bundles" => $bundles,
            "products" => $products,
            "graph" => $shopPageSchemaService->generate(),
        ]);
    }

    public function collection(ProductCacheService $productCacheService)
    {
        $bundles = $productCacheService->allBundles();

        return view("storefront.collections", ["bundles" => $bundles]);
    }
}
