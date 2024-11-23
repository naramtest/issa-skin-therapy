<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Faq\FaqService;

class FaqController extends Controller
{
    public function index(FaqService $faqService)
    {
        $faqSections = $faqService->getRegularFaqs();
        return view("storefront.faq", ["faqSections" => $faqSections]);
    }
}
