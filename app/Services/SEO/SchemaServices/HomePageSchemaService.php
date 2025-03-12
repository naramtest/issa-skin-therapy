<?php

namespace App\Services\SEO\SchemaServices;

use App\Helpers\Media\ImageGetter;
use App\Models\Info;
use App\Services\Currency\CurrencyHelper;
use App\Services\Info\InfoCacheService;
use Illuminate\Support\Facades\URL;
use Spatie\SchemaOrg\ItemAvailability;
use Spatie\SchemaOrg\Product;
use Spatie\SchemaOrg\Schema;

class HomePageSchemaService extends BaseSchemaService
{
    protected ?\App\Models\Product $featuredProduct;

    public function setFeaturedProduct(
        ?\App\Models\Product $featuredProduct
    ): static {
        $this->featuredProduct = $featuredProduct;
        return $this;
    }

    public function generate(): string
    {
        // Create WebSite schema
        $websiteSchema = Schema::webSite()
            ->name($this->info->name)
            ->url(URL::to("/"));

        // Create Organization schema
        $organizationSchema = $this->createOrganizationSchema();

        // Create WebPage schema
        $webPageSchema = $this->createWebPageSchema(
            $this->info->name,
            $this->info->about
        );

        // Create featured products schema
        $featuredProductsSchema = $this->generateFeaturedProductsSchema();

        // Combine all schemas
        return $this->combineSchemas([
            $websiteSchema,
            $organizationSchema,
            $webPageSchema,
            $featuredProductsSchema,
        ]);
    }

    protected function generateFeaturedProductsSchema(): ?Product
    {
        if (!$this->featuredProduct) {
            return null;
        }

        return Schema::product()
            ->name($this->featuredProduct->name)
            ->description(strip_tags($this->featuredProduct->description))
            ->image(ImageGetter::getMediaUrl($this->featuredProduct))
            ->brand(Schema::brand()->name($this->info->name))
            ->offers(
                Schema::offer()
                    ->price(
                        CurrencyHelper::decimalFormatter(
                            value: $this->featuredProduct->getCurrentPrice()
                        )
                    )
                    ->priceCurrency(CurrencyHelper::getCurrencyCode())
                    ->availability(ItemAvailability::InStock)
                    ->url(URL::route("product.show", $this->featuredProduct))
            );
    }

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
}
