<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Info\InfoCacheService;
use App\Services\Post\PostCacheService;
use App\Services\Product\ProductCacheService;
use App\Services\SEO\Schema;
use App\Traits\Seo\HasPageSeo;

class HomeController extends Controller
{
    use HasPageSeo;

    /**
     * @throws \Exception
     */
    public function index(
        ProductCacheService $productCacheService,
        PostCacheService $postCacheService,
        InfoCacheService $infoCacheService
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
            "graph" => Schema::getSchema(
                "home",
                data: $featuredProduct,
                info: $info
            ),
            "seo" => self::seoData(
                title: $info->name,
                description: $info->about,
                image: "storage/test/hero1.webp",

                tags: ["skincare", "beauty", "skin health", "cleanser"],
                author: $info->name
            ),
        ]);
    }
}
