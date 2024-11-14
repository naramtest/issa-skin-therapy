<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Faq\FaqService;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
    public function show(Product $product, FaqService $faqService)
    {
        $product->load(["media"]);
        $media = new Collection();
        $media[] = $product->getFirstMedia(config("const.media.featured"));

        $media = $media->merge(
            $product->getMedia(config("const.media.gallery"))
        );

        $productFaqs = $faqService->getProductFaqs();
        return view("storefront.product.show", [
            "product" => $product,
            "faqs" => $productFaqs,
            "media" => $media,
        ]);
    }
}
