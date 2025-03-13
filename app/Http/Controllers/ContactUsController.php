<?php

namespace App\Http\Controllers;

use App\Services\Info\InfoCacheService;
use App\Services\SEO\Schema;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class ContactUsController extends Controller
{
    public function index(InfoCacheService $infoCacheService)
    {
        $info = $infoCacheService->getInfo();
        return view("storefront.contact", [
            "graph" => Schema::getSchema("contact", info: $info),
            "seo" => new SEOData(
                title: getPageTitle(__("store.Contact Us")),
                description: __(
                    "store.Get in touch with our team for any inquiries or assistance"
                ),
                author: $info->name,
                image: "storage/test/hero2.webp",
                tags: [
                    "contact us",
                    "customer support",
                    "get in touch",
                    "help",
                    "support",
                ],
                site_name: getLocalAppName(),
                locale: app()->getLocale()
            ),
        ]);
    }
}
