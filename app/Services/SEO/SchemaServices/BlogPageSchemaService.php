<?php

namespace App\Services\SEO\SchemaServices;

use App\Helpers\Media\ImageGetter;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Spatie\SchemaOrg\Schema;
use Str;

class BlogPageSchemaService extends BaseSchemaService
{
    protected Collection $posts;

    public function setPosts(Collection $posts): static
    {
        $this->posts = $posts->sortBy("updated_at");
        return $this;
    }

    public function generate(): string
    {
        $this->getInfo();

        // Create Blog Listing Schema
        $blogPageSchema = Schema::collectionPage()
            ->name(getPageTitle(__("store.Blog")))
            ->description(
                __(
                    "store.Explore our articles and insights on skincare, beauty tips, and product recommendations"
                )
            )
            ->url(URL::route("posts.index"))
            ->datePublished($this->posts->last()->published_at)
            ->dateModified($this->posts->first()->published_at);

        // Create basic WebPage schema
        $webPageSchema = $this->createWebPageSchema(
            name: getPageTitle(__("store.Blog")),
            description: __(
                "store.Explore our articles and insights on skincare, beauty tips, and product recommendations"
            ),
            image: asset("storage/test/hero1.webp")
        );

        // Create Organization schema
        $organizationSchema = $this->createOrganizationSchema();

        // Create BlogPosting items for latest articles
        $blogPostSchemas = [];

        if (isset($this->posts) && $this->posts->isNotEmpty()) {
            /** @var Post $post */
            foreach ($this->posts->take(5) as $post) {
                $blogPostSchemas[] = Schema::blogPosting()
                    ->headline($post->title)
                    ->description(Str::limit(strip_tags($post->excerpt), 150))
                    ->datePublished($post->published_at)
                    ->dateModified($post->updated_at)
                    ->url(route("posts.show", $post->slug))
                    ->image(ImageGetter::getMediaUrl($post))
                    ->author(
                        Schema::person()
                            ->name($this->info->name)
                            ->url(URL::route("posts.index"))
                    );
            }
        }

        // Combine all schemas
        return $this->combineSchemas(
            array_merge(
                [$organizationSchema, $webPageSchema, $blogPageSchema],
                $blogPostSchemas
            )
        );
    }
}
