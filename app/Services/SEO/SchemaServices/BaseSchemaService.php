<?php

namespace App\Services\SEO\SchemaServices;

use App\Models\Info;
use Illuminate\Support\Facades\URL;
use Spatie\SchemaOrg\Organization;
use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\WebPage;

abstract class BaseSchemaService
{
    public function __construct(private readonly Info $info)
    {
    }

    /**
     * Generate the schema markup for the page
     *
     * @return string
     */
    abstract public function generate(): string;

    /**
     * Create a base WebPage schema
     *
     * @param string $name
     * @param string $description
     * @param string|null $image
     * @return WebPage
     */
    protected function createWebPageSchema(
        string $name,
        string $description,
        ?string $image = null
    ): WebPage {
        $webPage = Schema::webPage()
            ->name($name)
            ->description($description)
            ->url(URL::current());

        // Add image if provided
        if ($image) {
            $webPage->image($image);
        }

        return $webPage;
    }

    /**
     * Create a base Organization schema
     *
     * @return Organization
     */
    protected function createOrganizationSchema(): Organization
    {
        $contactPoint = Schema::contactPoint()->contactType("Customer Service");
        if (count($this->info->email)) {
            $contactPoint->email($this->info->email[0]["email"]);
        }
        if (count($this->info->phone)) {
            $contactPoint->telephone($this->info->phone[0]["number"]);
        }
        return Schema::organization()
            ->name($this->info->name)
            ->url(URL::to("/"))
            ->logo(asset("storage/images/issa-logo.webp"))
            ->contactPoint($contactPoint);
    }

    /**
     * Combine multiple schemas into a single script
     *
     * @param array $schemas
     * @return string
     */
    protected function combineSchemas(array $schemas): string
    {
        $combinedSchema = Schema::itemList()->itemListElement($schemas);

        return $combinedSchema->toScript();
    }
}
