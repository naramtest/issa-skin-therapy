<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Product;
use App\Services\Faq\FaqService;

class ProductController extends Controller
{
    public function show(Product $product, FaqService $faqService)
    {
        $product->load(["media", "categories", "types"]);
        $productFaqs = $faqService->getProductFaqs();
        return view("storefront.product.show", [
            "product" => $product,
            "faqs" => $productFaqs,
            "media" => $product->media,
        ]);
    }

    public function showBundle(Bundle $bundle, FaqService $faqService)
    {
        $bundle->load(["media", "products"]);
        $productFaqs = $faqService->getProductFaqs();
        return view("storefront.product.bundle", [
            "bundle" => $bundle,
            "faqs" => $productFaqs,
            "media" => $bundle->media,
        ]);
    }
}
