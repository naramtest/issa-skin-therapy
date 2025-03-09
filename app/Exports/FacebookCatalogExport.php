<?php

namespace App\Exports;

use App\Helpers\Media\ImageGetter;
use App\Models\Bundle;
use App\Models\Product;
use App\Services\Currency\CurrencyHelper;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FacebookCatalogExport implements FromCollection, WithHeadings, WithMapping
{
    protected Collection $products;
    protected Collection $bundles;

    public function __construct()
    {
        // Get all published products and bundles
        $this->products = Product::published()->available()->get();
        $this->bundles = Bundle::published()->get();
    }

    public function collection(): Collection
    {
        return $this->products->concat($this->bundles);
    }

    public function headings(): array
    {
        return [
            "id",
            "title",
            "description",
            "availability",
            "condition",
            "price",
            "sale_price",
            "sale_price_effective_date",
            "link",
            "image_link",
            "additional_image_link",
            "brand",
            "google_product_category",
            "fb_product_category",
            "quantity_to_sell_on_facebook",
            "product_type",
        ];
    }

    public function map($item): array
    {
        // Determine if it's a product or bundle
        $isProduct = $item instanceof Product;

        // Get base URL for product or bundle links
        $baseUrl = $isProduct
            ? route("product.show", ["product" => $item->slug])
            : route("product.bundle", ["bundle" => $item->slug]);

        // Determine price and sale price
        $regularPrice = $item->regular_price;
        $salePrice = $item->sale_price;
        $isSaleActive = $item->isOnSale();

        // Get main product image and additional images
        $mainImageUrl = ImageGetter::getMediaUrl($item);

        $additionalImageLink = ImageGetter::getGalleryImages($item);

        // Determine inventory
        $inventory = $item->inventory()->getCurrentQuantity();

        // Determine availability
        $availability = $inventory > 0 ? "in stock" : "out of stock";

        // Format sale price dates if applicable
        $salePriceEffectiveDate = null;
        if ($isSaleActive && $item->sale_starts_at && $item->sale_ends_at) {
            $salePriceEffectiveDate =
                $item->sale_starts_at->format("Y-m-d\T23:59+00:00") .
                "/" .
                $item->sale_ends_at->format("Y-m-d\T23:59+00:00");
        }

        // Get product type or category
        $productType = $isProduct
            ? ($item->types()->exists()
                ? $item->categories->first()->name
                : "Skin Care")
            : "Bundle";

        return [
            $item->facebook_id,
            $item->name,
            strip_tags($item->description) ?? "",
            $availability,
            "new",
            CurrencyHelper::faceBookCurrency($regularPrice),
            $isSaleActive ? CurrencyHelper::faceBookCurrency($salePrice) : null,
            $salePriceEffectiveDate,
            $baseUrl,
            $mainImageUrl,
            $additionalImageLink,
            config("app.name"),
            "Health & Beauty > Personal Care > Cosmetics > Skin Care",
            "health & beauty > beauty > skin care",
            $inventory,
            $productType,
        ];
    }
}
