<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductType;
use App\Services\Faq\FaqService;
use App\Services\SEO\SchemaServices\BundlePageSchemaService;
use App\Services\SEO\SchemaServices\SingleProductSchemaService;

class ProductController extends Controller
{
    public function show(
        Product $product,
        FaqService $faqService,
        SingleProductSchemaService $schemaService
    ) {
        $product->load(["media", "categories", "types"]);
        $productFaqs = $faqService->getProductFaqs();
        return view("storefront.product.show", [
            "product" => $product,
            "faqs" => $productFaqs,
            "media" => $product->media,
            "graph" => $schemaService->setProduct($product)->generate(),
        ]);
    }

    public function showBundle(
        Bundle $bundle,
        FaqService $faqService,
        BundlePageSchemaService $schemaService
    ) {
        $bundle->load(["media", "products"]);
        $productFaqs = $faqService->getProductFaqs();
        return view("storefront.product.bundle", [
            "bundle" => $bundle,
            "faqs" => $productFaqs,
            "media" => $bundle->media,
            "graph" => $schemaService->setBundle($bundle)->generate(),
        ]);
    }

    public function showProductCategory(string $slug)
    {
        //       TODO: cache this route
        $category = Category::where("slug", $slug)
            ->product()
            ->visible()
            ->first();

        if ($category) {
            $products = $category->products;
        } else {
            // If not category, try to find type
            $type = ProductType::where("slug", $slug)->first();

            if ($type) {
                $products = $type->products;
            } else {
                abort(404);
            }
        }
        return view("storefront.product.category", [
            "collectionType" => $category ?? $type,
            "products" => $products,
        ]);
    }
}
