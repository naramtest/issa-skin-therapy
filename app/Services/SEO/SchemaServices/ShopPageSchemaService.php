<?php

namespace App\Services\SEO\SchemaServices;

use App\Helpers\Media\ImageGetter;
use App\Models\Info;
use App\Models\Product;
use App\Services\Currency\CurrencyHelper;
use App\Services\Info\InfoCacheService;
use App\Services\Product\ProductCacheService;
use Illuminate\Support\Facades\URL;
use Spatie\SchemaOrg\ItemAvailability;
use Spatie\SchemaOrg\Schema;

class ShopPageSchemaService extends BaseSchemaService
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
        // Create WebPage schema
        $webPageSchema = $this->createWebPageSchema(
            name: getPageTitle(__("store.Shop")),
            description: __(
                "store.Browse our curated collection of high-quality products. Find everything you need with easy navigation, detailed descriptions, and secure checkout"
            ),
            image: asset("storage/images/shop.webp")
        );

        // Create Product Collection schema
        $productsSchema = $this->generateProductsSchema();

        // Combine schemas
        return $this->combineSchemas([
            $this->createOrganizationSchema(),
            $webPageSchema,
            ...$productsSchema,
        ]);
    }

    protected function generateProductsSchema(): array
    {
        $products = $this->productCacheService->allProducts();

        $bundles = $this->productCacheService->allBundles();

        return $products
            ->concat($bundles)
            ->map(function ($product) {
                return Schema::product()
                    ->name($product->name)
                    ->description(strip_tags($product->description))
                    ->image(ImageGetter::getMediaUrl($product))
                    ->brand(Schema::brand()->name(config("app.name")))
                    ->offers(
                        Schema::offer()
                            ->price(
                                CurrencyHelper::decimalFormatter(
                                    value: $product->getCurrentPrice()
                                )
                            )
                            ->priceCurrency(CurrencyHelper::getCurrencyCode())
                            ->availability(
                                $product->inventory()->isInStock()
                                    ? ItemAvailability::InStock
                                    : ItemAvailability::OutOfStock
                            )
                            ->url(
                                $product instanceof Product
                                    ? URL::route("product.show", $product)
                                    : URL::route("product.bundle", $product)
                            )
                    );
            })
            ->toArray();
    }
}
