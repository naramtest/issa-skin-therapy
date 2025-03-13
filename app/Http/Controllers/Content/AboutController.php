<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Services\Info\InfoCacheService;
use App\Services\SEO\Schema;
use App\Traits\Seo\HasPageSeo;

class AboutController extends Controller
{
    use HasPageSeo;

    public function index(InfoCacheService $infoCacheService)
    {
        $info = $infoCacheService->getInfo();
        return view("storefront.about", [
            "graph" => Schema::getSchema("about", info: $info),
            "seo" => self::seoData(
                title: getPageTitle(__("store.About Us")),
                description: $info->about,
                image: "storage/images/about.webp",

                tags: [
                    "about us",
                    "our story",
                    "skincare brand",
                    "company mission",
                    "values",
                ],
                author: $info->name
            ),
        ]);
    }
}
