<?php

namespace App\Http\Controllers;

use App\Services\Info\InfoCacheService;
use App\Services\SEO\Schema;
use App\Traits\Seo\HasPageSeo;

class ContactUsController extends Controller
{
    use HasPageSeo;

    public function index(InfoCacheService $infoCacheService)
    {
        $info = $infoCacheService->getInfo();
        return view("storefront.contact", [
            "graph" => Schema::getSchema("contact", info: $info),
            "seo" => self::seoData(
                title: getPageTitle(__("store.Contact Us")),
                description: __(
                    "store.Get in touch with our team for any inquiries or assistance"
                ),
                image: "storage/test/hero2.webp",
                tags: [
                    "contact us",
                    "customer support",
                    "get in touch",
                    "help",
                    "support",
                ],
                author: $info->name
            ),
        ]);
    }
}
