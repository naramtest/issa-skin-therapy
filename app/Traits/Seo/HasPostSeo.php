<?php

namespace App\Traits\Seo;

use App\Helpers\Media\ImageGetter;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

trait HasPostSeo
{
    use HasSEO;

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: getPageTitle(
                substr(
                    !empty($this->meta_title)
                        ? $this->meta_title
                        : $this->title,
                    0,
                    60 - strlen(getLocalAppName())
                )
            ),
            description: substr(
                strip_tags(
                    !empty($this->meta_description)
                        ? $this->meta_description
                        : $this->excerpt
                ),
                0,
                160
            ),
            author: getLocalAppName(),
            image: ImageGetter::getRelativePath($this),
            url: route("posts.show", ["post" => $this->slug]),
            published_time: $this->published_at,
            modified_time: $this->updated_at,
            articleBody: strip_tags($this->excerpt),
            section: $this->categories->first()?->name ?? __("store.General"),
            tags: $this->tags->pluck("name")->toArray(),
            type: "article",
            site_name: getLocalAppName(),
            canonical_url: route("posts.show", ["post" => $this->slug])
        );
    }
}
