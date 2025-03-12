<?php

namespace App\Services\SEO\SchemaServices;

use App\Helpers\Media\ImageGetter;
use App\Models\Info;
use App\Services\Currency\CurrencyHelper;
use App\Services\Info\InfoCacheService;
use App\Services\Product\ProductCacheService;
use Illuminate\Support\Facades\URL;
use Spatie\SchemaOrg\ItemAvailability;
use Spatie\SchemaOrg\Product;
use Spatie\SchemaOrg\Schema;

class HomePageSchemaService extends BaseSchemaService
{
    protected ProductCacheService $productCacheService;
    private Info $info;

    public function __construct(
        ProductCacheService $productCacheService,
        InfoCacheService $infoCacheService
    ) {
        $this->info = $infoCacheService->getInfo();
        $this->productCacheService = $productCacheService;

        parent::__construct($this->info);
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
        $featuredProduct = $this->productCacheService->getFeaturedProduct();

        if (!$featuredProduct) {
            return null;
        }

        return Schema::product()
            ->name($featuredProduct->name)
            ->description(strip_tags($featuredProduct->description))
            ->image(ImageGetter::getMediaUrl($featuredProduct))
            ->brand(Schema::brand()->name($this->info->name))
            ->offers(
                Schema::offer()
                    ->price(
                        CurrencyHelper::decimalFormatter(
                            value: $featuredProduct->getCurrentPrice()
                        )
                    )
                    ->priceCurrency(CurrencyHelper::getCurrencyCode())
                    ->availability(ItemAvailability::InStock)
                    ->url(URL::route("product.show", $featuredProduct))
            );
    }
}
