<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Faq\FaqService;
use App\Services\Info\InfoCacheService;
use App\Services\SEO\Schema;
use Carbon\Carbon;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class FaqController extends Controller
{
    public function index(
        FaqService $faqService,
        InfoCacheService $infoCacheService
    ) {
        $faqSections = $faqService->getRegularFaqs();
        $info = $infoCacheService->getInfo();

        return view("storefront.faq", [
            "faqSections" => $faqSections,
            "graph" => Schema::getSchema("faq", $faqSections, $info),
            "seo" => new SEOData(
                title: getPageTitle(__("store.FAQ")),
                description: __(
                    "store.Find answers to frequently asked questions about our products"
                ),
                author: $info->name,
                image: "storage/test/hero1.webp",
                enableTitleSuffix: true,
                published_time: Carbon::parse("2024-06-15"),
                modified_time: Carbon::parse("2024-11-20"),
                section: "Help",
                tags: [
                    "faq",
                    "help",
                    "questions",
                    "answers",
                    "support",
                    "guidance",
                ],
                site_name: getLocalAppName(),
                locale: app()->getLocale()
            ),
        ]);
    }
}
