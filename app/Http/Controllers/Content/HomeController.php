<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Invoice\InvoiceService;
use App\Services\Post\PostCacheService;
use App\Services\Product\ProductCacheService;

class HomeController extends Controller
{
    /**
     * @throws \Exception
     */
    public function index(
        ProductCacheService $productCacheService,
        PostCacheService $postCacheService
    ) {
        $first = app(InvoiceService::class)->generateInvoice(
            Order::latest()->first()
        );

        return $first->toPdfInvoice()->view();
        $bundles = $productCacheService->allBundles();
        $categories = $productCacheService->allProductCategories();
        $featuredProduct = $productCacheService->getFeaturedProduct();
        $posts = $postCacheService->getHomePost();
        return view("storefront.home", [
            "bundles" => $bundles,
            "categories" => $categories,
            "featuredProduct" => $featuredProduct,
            "posts" => $posts,
        ]);
    }
}
