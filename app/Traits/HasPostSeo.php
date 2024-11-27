<?php

namespace App\Traits;

use App\Models\Post;
use RalphJSmit\Laravel\SEO\SchemaCollection;
use RalphJSmit\Laravel\SEO\Support\SEOData;

trait HasPostSeo
{
    public static function getPostSeoData(Post $post): SEOData
    {
        $image = null;

        $media = $post->getFirstMedia(config("const.media.post.featured"));
        if ($media) {
            $conversion = $media->hasGeneratedConversion(
                config("const.media.optimized")
            )
                ? config("const.media.optimized")
                : "";
            $image = "storage/" . $media->getPathRelativeToRoot($conversion);
        }

        return new SEOData(
            title: $post->meta_title ?? substr($post->title, 0, 60),
            description: $post->description ?? substr($post->excerpt, 0, 160),
            author: $post->author->name,
            image: $image,
            url: route("posts.show", ["post" => $post->slug]),
            published_time: $post->published_at,
            section: $post->categories->first()?->name ?? __("store.General"),
            tags: $post->tags->pluck("name")->toArray(),
            schema: SchemaCollection::initialize()->addArticle(),
            type: "article",
            site_name: config("app.name"),
            canonical_url: route("posts.show", ["post" => $post->slug])
        );
    }
}
