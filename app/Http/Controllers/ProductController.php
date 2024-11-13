<?php

namespace App\Http\Controllers;

use App\Services\Faq\FaqService;

class ProductController extends Controller
{
    public function index(FaqService $faqService)
    {
        $productFaqs = $faqService->getProductFaqs();
        return view("storefront.product.show", ["faqs" => $productFaqs]);
    }
}
