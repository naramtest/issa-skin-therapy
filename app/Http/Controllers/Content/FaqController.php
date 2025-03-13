<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Faq\FaqService;
use App\Services\Info\InfoCacheService;
use App\Services\SEO\Schema;
use App\Traits\Seo\HasPageSeo;

class FaqController extends Controller
{
    use HasPageSeo;

    public function index(
        FaqService $faqService,
        InfoCacheService $infoCacheService
    ) {
        $faqSections = $faqService->getRegularFaqs();
        $info = $infoCacheService->getInfo();

        return view("storefront.faq", [
            "faqSections" => $faqSections,
            "graph" => Schema::getSchema("faq", $faqSections, $info),
            "seo" => self::seoData(
                title: getPageTitle(__("store.FAQ")),
                description: __(
                    "store.Find answers to frequently asked questions about our products"
                ),
                image: "storage/test/hero1.webp",
                tags: [
                    "faq",
                    "help",
                    "questions",
                    "answers",
                    "support",
                    "guidance",
                ],
                section: "Help",
                author: $info->name
            ),
        ]);
    }
}
