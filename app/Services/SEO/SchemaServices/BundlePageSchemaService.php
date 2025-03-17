<?php

namespace App\Services\SEO\SchemaServices;

use App\Helpers\Media\ImageGetter;
use App\Models\Bundle;
use App\Services\Currency\CurrencyHelper;
use App\Traits\Seo\HasReturnPolicy;
use Illuminate\Support\Facades\URL;
use Spatie\SchemaOrg\ItemAvailability;
use Spatie\SchemaOrg\Schema;

class BundlePageSchemaService extends BaseSchemaService
{
    use HasReturnPolicy;

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

    protected function createBundleSchema(): \Spatie\SchemaOrg\Product
    {
        $bundle = $this->bundle;

        // Create the base offer schema
        $offerSchema = Schema::offer()
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
            ->hasMerchantReturnPolicy(self::getMerchantReturnPolicy())
            ->url(URL::route("product.bundle", $bundle));

        $bundleSchema = Schema::product()
            ->name($bundle->name)
            ->description(strip_tags($bundle->description) ?? "")
            ->brand(Schema::brand()->name($this->info->name))
            ->image(ImageGetter::getMediaUrl($bundle))
            ->offers($offerSchema);

        // Add included products as parts of the bundle
        $includedProducts = $bundle->products->map(function ($product) {
            $productOfferSchema = Schema::offer()
                ->price(
                    CurrencyHelper::decimalFormatter(
                        value: $product->getCurrentPrice()
                    )
                )
                ->hasMerchantReturnPolicy(self::getMerchantReturnPolicy())
                ->priceCurrency(CurrencyHelper::getCurrencyCode());

            return Schema::product()
                ->name($product->name)
                ->description(strip_tags($product->description) ?? "")
                ->image(ImageGetter::getMediaUrl($product))
                ->offers($productOfferSchema);
        });

        $bundleSchema->isRelatedTo($includedProducts->toArray());

        return $bundleSchema;
    }
}
