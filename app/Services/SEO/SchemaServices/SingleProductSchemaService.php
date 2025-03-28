<?php

namespace App\Services\SEO\SchemaServices;

use App\Helpers\Media\ImageGetter;
use App\Models\Product;
use App\Services\Currency\CurrencyHelper;
use App\Traits\Seo\HasReturnPolicy;
use Illuminate\Support\Facades\URL;
use Spatie\SchemaOrg\ItemAvailability;
use Spatie\SchemaOrg\Schema;

class SingleProductSchemaService extends BaseSchemaService
{
    use HasReturnPolicy;

    protected Product $product;

    public function setProduct(Product $product): static
    {
        $this->product = $product;
        return $this;
    }

    public function generate(): string
    {
        // Create Product schema
        $productSchema = $this->createProductSchema();

        // Create WebPage schema
        $webPageSchema = $this->createWebPageSchema(
            getPageTitle($this->product->name),
            strip_tags($this->product->description) ?? "",
            ImageGetter::getMediaUrl($this->product)
        );

        // Combine schemas
        return $this->combineSchemas([
            $this->createOrganizationSchema(),
            $webPageSchema,
            $productSchema,
        ]);
    }

    protected function createProductSchema(): \Spatie\SchemaOrg\Product
    {
        $product = $this->product;

        // Create the base offer schema
        $offerSchema = Schema::offer()
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
            ->hasMerchantReturnPolicy(self::getMerchantReturnPolicy())
            ->url(URL::route("product.show", $product));

        // Create the complete product schema
        $productSchema = Schema::product()
            ->name($product->name)
            ->description(strip_tags($product->description) ?? "")
            ->brand(Schema::brand()->name($this->info->name))
            ->image(ImageGetter::getMediaUrl($product))
            ->offers($offerSchema);
        if ($product->key_ingredients) {
            $productSchema->additionalProperty(
                Schema::propertyValue()
                    ->name("Key Ingredients")
                    ->value(strip_tags($product->key_ingredients))
            );
        }

        return $productSchema;
    }
}
