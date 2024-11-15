<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Faq\FaqService;

class ProductController extends Controller
{
    public function show(Product $product, FaqService $faqService)
    {
        $product->load(["media"]);
        $productFaqs = $faqService->getProductFaqs();
        return view("storefront.product.show", [
            "product" => $product,
            "faqs" => $productFaqs,
            "media" => $product->media,
        ]);
    }
}
