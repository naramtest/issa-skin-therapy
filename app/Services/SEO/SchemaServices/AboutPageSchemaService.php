<?php

namespace App\Services\SEO\SchemaServices;

use Illuminate\Support\Facades\URL;
use Spatie\SchemaOrg\Schema;

class AboutPageSchemaService extends BaseSchemaService
{
    public function generate(): string
    {
        $this->getInfo();
        // Create AboutPage schema
        $aboutPageSchema = Schema::aboutPage()
            ->name(getPageTitle(__("store.About Us")))
            ->description($this->info->about)
            ->url(URL::route("about.index"))
            ->datePublished(now()->subYear())
            ->dateModified(now())
            ->image(asset("storage/images/about-us-hero.webp"));

        // Create Organization schema with additional properties specific to About page
        $organizationSchema = $this->createOrganizationSchema()
            ->slogan($this->info->slogan ?? "")
            ->description($this->info->about);

        // Create WebPage schema
        $webPageSchema = $this->createWebPageSchema(
            name: getPageTitle(__("store.About Us")),
            description: $this->info->about,
            image: asset("storage/images/about-us-hero.webp")
        );

        // Combine schemas
        return $this->combineSchemas([
            $organizationSchema,
            $webPageSchema,
            $aboutPageSchema,
        ]);
    }
}
