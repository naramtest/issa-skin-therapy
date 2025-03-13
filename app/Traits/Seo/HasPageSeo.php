<?php

namespace App\Traits\Seo;

use Carbon\Carbon;
use RalphJSmit\Laravel\SEO\Support\SEOData;

trait HasPageSeo
{
    public static function seoData(
        string $title,
        string $description,
        string $image,
        array $tags,
        ?string $section = null,

        ?string $author = null,
        ?string $type = "website"
    ): SEOData {
        return new SEOData(
            title: $title,
            description: $description,
            author: $author ?? getLocalAppName(),
            image: $image,
            enableTitleSuffix: true,
            published_time: Carbon::parse("2024-06-15"),
            modified_time: Carbon::parse("2024-11-20"),
            section: $section,
            tags: $tags,
            type: $type,
            site_name: getLocalAppName(),
            locale: app()->getLocale()
        );
    }
}
