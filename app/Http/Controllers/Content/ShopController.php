<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductCacheService;
use App\Services\SEO\Schema;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class ShopController extends Controller
{
    public function index(ProductCacheService $productCacheService)
    {
        $bundles = $productCacheService->allBundles();
        $products = $productCacheService->getPaginatedProduct(12);
        return view("storefront.shop", [
            "bundles" => $bundles,
            "products" => $products,
            "graph" => Schema::getSchema("shop", [
                "products" => $productCacheService->allProducts(),
                "bundles" => $bundles,
            ]),
            "seo" => new SEOData(
                title: getPageTitle(__("store.Shop")),
                description: __(
                    "store.Browse our curated collection of high-quality products. Find everything you need with easy navigation, detailed descriptions, and secure checkout"
                ),
                image: "storage/images/shop.webp",
                tags: [
                    "skincare",
                    "beauty",
                    "skin health",
                    "cleanser",
                    "shop",
                    "products",
                    "collections",
                ]
            ),
        ]);
    }

    public function collection(ProductCacheService $productCacheService)
    {
        $bundles = $productCacheService->allBundles();

        return view("storefront.collections", ["bundles" => $bundles]);
    }
}
