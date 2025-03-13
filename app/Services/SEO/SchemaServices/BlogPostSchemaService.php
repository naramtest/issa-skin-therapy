<?php

namespace App\Services\SEO\SchemaServices;

use App\Helpers\Media\ImageGetter;
use App\Models\Post;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Spatie\SchemaOrg\Schema;

class BlogPostSchemaService extends BaseSchemaService
{
    protected Post $post;

    public function setPost(Post $post): static
    {
        $this->post = $post;
        return $this;
    }

    public function generate(): string
    {
        $this->getInfo();

        // Get the post data
        $post = $this->post;
        $featuredImage = ImageGetter::getMediaUrl($post);

        // Create BlogPosting schema
        $blogPostSchema = Schema::blogPosting()
            ->headline($post->title)
            ->articleBody(strip_tags($post->excerpt))
            ->datePublished($post->published_at)
            ->dateModified($post->updated_at)
            ->image($featuredImage)
            ->mainEntityOfPage(URL::route("posts.show", $post->slug))
            ->author(Schema::person()->name($this->info->name));

        // Add categories as keywords if available
        if ($post->categories->isNotEmpty()) {
            $blogPostSchema->keywords(
                $post->tags->pluck("name")->implode(", ")
            );
        }

        // Create WebPage schema for the post
        $webPageSchema = $this->createWebPageSchema(
            name: getPageTitle($post->title),
            description: Str::limit(
                strip_tags($post->excerpt ?? $post->body),
                160
            ),
            image: $featuredImage
        );

        // Create breadcrumbs for the post
        $breadcrumbsSchema = Schema::breadcrumbList()->itemListElement([
            Schema::listItem()
                ->position(1)
                ->name(__("store.Home"))
                ->item(URL::to("/")),
            Schema::listItem()
                ->position(2)
                ->name(__("store.Blog"))
                ->item(URL::route("posts.index")),
            Schema::listItem()
                ->position(3)
                ->name($post->title)
                ->item(URL::route("posts.show", $post->slug)),
        ]);

        // Combine all schemas
        return $this->combineSchemas(
            array_merge([
                $this->createOrganizationSchema(),
                $webPageSchema,
                $blogPostSchema,
                $breadcrumbsSchema,
            ])
        );
    }
}
