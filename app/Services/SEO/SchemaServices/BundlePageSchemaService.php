<?php

namespace App\Services\SEO\SchemaServices;

use App\Helpers\Media\ImageGetter;
use App\Models\Bundle;
use App\Services\Currency\CurrencyHelper;
use Illuminate\Support\Facades\URL;
use Spatie\SchemaOrg\ItemAvailability;
use Spatie\SchemaOrg\Product;
use Spatie\SchemaOrg\Schema;

class BundlePageSchemaService extends BaseSchemaService
{
    protected Bundle $bundle;

    public function setBundle(Bundle $bundle): static
    {
        $this->bundle = $bundle;
        return $this;
    }

    public function generate(): string
    {
        $bundleSchema = $this->createBundleSchema();

        $webPageSchema = $this->createWebPageSchema(
            getPageTitle($this->bundle->name),
            strip_tags($this->bundle->description) ?? "",
            ImageGetter::getMediaUrl($this->bundle)
        );

        // Combine schemas
        return $this->combineSchemas([
            $this->createOrganizationSchema(),
            $webPageSchema,
            $bundleSchema,
        ]);
    }

    protected function createBundleSchema(): Product
    {
        $bundle = $this->bundle;

        $bundleSchema = Schema::product()
            ->name($bundle->name)
            ->description(strip_tags($bundle->description) ?? "")
            ->brand(Schema::brand()->name($this->info->name))
            ->image(ImageGetter::getMediaUrl($bundle))
            ->offers(
                Schema::offer()
                    ->price(
                        CurrencyHelper::decimalFormatter(
                            value: $bundle->getCurrentPrice()
                        )
                    )
                    ->priceCurrency(CurrencyHelper::getCurrencyCode())
                    ->availability(
                        $bundle->inventory()->isInStock()
                            ? ItemAvailability::InStock
                            : ItemAvailability::OutOfStock
                    )
                    ->url(URL::route("product.bundle", $bundle))
            );

        // Add included products as parts of the bundle
        $includedProducts = $bundle->products->map(function ($product) {
            return Schema::product()
                ->name($product->name)
                ->description(strip_tags($product->description) ?? "")
                ->image(ImageGetter::getMediaUrl($product))
                ->offers(
                    Schema::offer()
                        ->price(
                            CurrencyHelper::decimalFormatter(
                                value: $product->getCurrentPrice()
                            )
                        )
                        ->priceCurrency(CurrencyHelper::getCurrencyCode())
                );
        });

        $bundleSchema->isRelatedTo($includedProducts->toArray());

        return $bundleSchema;
    }
}
