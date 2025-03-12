<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Info\InfoCacheService;
use App\Services\Post\PostCacheService;
use App\Services\Product\ProductCacheService;
use App\Services\SEO\SchemaServices\HomePageSchemaService;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class HomeController extends Controller
{
    /**
     * @throws \Exception
     */
    public function index(
        ProductCacheService $productCacheService,
        PostCacheService $postCacheService,
        InfoCacheService $infoCacheService,
        HomePageSchemaService $homePageSchemaService
    ) {
        $bundles = $productCacheService->allBundles();
        $categories = $productCacheService->allProductCategories();
        $featuredProduct = $productCacheService->getFeaturedProduct();
        $posts = $postCacheService->getHomePost();
        $info = $infoCacheService->getInfo();
        return view("storefront.home", [
            "bundles" => $bundles,
            "categories" => $categories,
            "featuredProduct" => $featuredProduct,
            "posts" => $posts,
            "graph" => $homePageSchemaService->generate(),
            "seo" => new SEOData(
                title: $info->name,
                description: $info->about,
                author: $info->name,
                image: "storage/test/hero1.webp",
                tags: [
                    "skincare",
                    "beauty",
                    "natural skincare",
                    "skin health",
                    "moisturizer",
                    "cleanser",
                    "serum",
                ]
            ),
        ]);
    }
}
