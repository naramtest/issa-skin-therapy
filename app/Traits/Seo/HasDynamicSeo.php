<?php

namespace App\Traits\Seo;

use App\Helpers\Media\ImageGetter;
use App\Models\Bundle;
use App\Models\Product;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;

trait HasDynamicSeo
{
    use HasSEO;

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: getPageTitle(
                substr($this->name, 0, 60 - strlen(getLocalAppName()))
            ),
            description: substr(strip_tags($this->description), 0, 160),
            author: getLocalAppName(),
            image: ImageGetter::getRelativePath($this),
            url: $this->getUrl(),
            published_time: $this->published_at,
            modified_time: $this->updated_at,
            articleBody: strip_tags($this->description),
            section: $this->getSection(),
            tags: $this->getTags(),
            type: "product",
            site_name: getLocalAppName(),
            canonical_url: $this->getUrl()
        );
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this instanceof Product
            ? route("product.show", $this)
            : route("product.bundle", $this);
    }

    /**
     * @return ?string
     */
    public function getSection(): ?string
    {
        return match (true) {
            $this instanceof Product => $this->categories->first()?->name ??
                __("store.General"),
            $this instanceof Bundle => "Collections",
            default => null,
        };
    }

    public function getTags()
    {
        return match (true) {
            $this instanceof Product => $this->tags->pluck("name")->toArray(),
            $this instanceof Bundle => $this->getTagsForBundle($this),
            default => null,
        };
    }

    protected function getTagsForBundle(Bundle $bundle): array
    {
        $tags = ["skincare bundle", "product collection", "skincare set"];
        $tags[] = $bundle->name;
        foreach ($bundle->products as $product) {
            foreach ($product->categories as $category) {
                $tags[] = $category->name;
            }

            foreach ($product->types as $type) {
                $tags[] = $type->name;
            }
        }
        return array_unique($tags);
    }
}
