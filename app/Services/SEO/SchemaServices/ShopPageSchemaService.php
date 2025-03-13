<?php

namespace App\Services\SEO\SchemaServices;

use App\Helpers\Media\ImageGetter;
use App\Models\Product;
use App\Services\Currency\CurrencyHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Spatie\SchemaOrg\ItemAvailability;
use Spatie\SchemaOrg\Schema;

class ShopPageSchemaService extends BaseSchemaService
{
    protected Collection $products;
    protected Collection $bundles;

    public function setProducts(Collection $products): static
    {
        $this->products = $products;
        return $this;
    }

    public function setBundles(Collection $bundles): static
    {
        $this->bundles = $bundles;
        return $this;
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
        return $this->products
            ->concat($this->bundles)
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
