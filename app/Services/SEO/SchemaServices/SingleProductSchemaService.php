<?php

namespace App\Services\SEO\SchemaServices;

use App\Helpers\Media\ImageGetter;
use App\Models\Info;
use App\Models\Product;
use App\Services\Currency\CurrencyHelper;
use Illuminate\Support\Facades\URL;
use Spatie\SchemaOrg\ItemAvailability;
use Spatie\SchemaOrg\Schema;

class SingleProductSchemaService extends BaseSchemaService
{
    protected Product $product;

    private Info $info;

    public function __construct(Product $product, Info $info)
    {
        $this->info = $info;
        $this->product = $product;

        parent::__construct($this->info);
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

        $productSchema = Schema::product()
            ->name($product->name)
            ->description(strip_tags($product->description) ?? "")
            ->brand(Schema::brand()->name($this->info->name))
            ->image(ImageGetter::getMediaUrl($product))
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
                    ->url(URL::route("product.show", $product))
            );

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
