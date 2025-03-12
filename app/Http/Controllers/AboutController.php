<?php

namespace App\Http\Controllers;

use App\Services\Info\InfoCacheService;
use App\Services\SEO\Schema;
use RalphJSmit\Laravel\SEO\Support\SEOData;

class AboutController extends Controller
{
    public function index(InfoCacheService $infoCacheService)
    {
        $info = $infoCacheService->getInfo();
        return view("storefront.about", [
            "graph" => Schema::getSchema("about", info: $info),
            "seo" => new SEOData(
                title: getPageTitle(__("store.About Us")),
                description: $info->about,
                author: $info->name,
                image: "storage/images/about.webp",
                tags: [
                    "about us",
                    "our story",
                    "skincare brand",
                    "company mission",
                    "values",
                ]
            ),
        ]);
    }
}
