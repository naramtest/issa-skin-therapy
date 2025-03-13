<?php

namespace App\Services\SEO\SchemaServices;

use App\Models\Info;
use App\Services\Info\InfoCacheService;
use Illuminate\Support\Facades\URL;
use Spatie\SchemaOrg\Organization;
use Spatie\SchemaOrg\Schema;
use Spatie\SchemaOrg\WebPage;

abstract class BaseSchemaService
{
    protected ?Info $info;

    /**
     * Generate the schema markup for the page
     *
     * @return string
     */
    abstract public function generate(): string;

    protected function getInfo(): Info
    {
        if (!$this->info) {
            $this->setInfo(app(InfoCacheService::class)->getInfo());
        }
        return $this->info;
    }

    /**
     * Set the info object and initialize parent
     *
     * @param Info $info
     * @return self
     */
    public function setInfo(Info $info): static
    {
        $this->info = $info;
        return $this;
    }

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
        $scriptTags = [];
        foreach ($schemas as $schema) {
            if ($schema !== null) {
                $scriptTags[] = $schema->toScript();
            }
        }

        return implode("\n", $scriptTags);
    }
}
