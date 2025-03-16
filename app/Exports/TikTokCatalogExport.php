<?php

namespace App\Exports;

class TikTokCatalogExport extends BaseCatalogExport
{
    public function headings(): array
    {
        return [
            "sku_id", // Unique identifier for the product
            "title", // Product name
            "description", // Product description
            "availability", // Stock status (in stock, out of stock)
            "condition", // Product condition (new, used, refurbished)
            "price", // Regular price
            "sale_price", // Discounted price
            "link", // URL to the product page
            "image_link", // Main product image URL
            "additional_image_link", // Additional product images
            "brand", // Brand name
            "product_type", // Product category/type
            "shipping_weight", // Weight for shipping calculation
            "shipping", // Shipping cost and country information
            "shipping_length", // Product length for shipping
            "shipping_width", // Product width for shipping
            "shipping_height", // Product height for shipping
            "tax", // Tax information
            "google_product_category", // Google taxonomy classification
            "sale_price_effective_date",
        ];
    }

    public function map($item): array
    {
        // Get item details using base class methods
        $mainImageUrl = $this->getMainImageUrl($item);
        $additionalImages = $this->getAdditionalImages($item);
        $itemUrl = $this->getItemUrl($item);
        $availability = $this->getAvailabilityStatus($item);

        // Format price data
        $regularPrice = $this->formatPrice($item->regular_price);
        $salePrice = $this->isOnSale($item)
            ? $this->formatPrice($item->sale_price)
            : null;

        // Calculate dimensions
        $length = $item->length ?? 0;
        $width = $item->width ?? 0;
        $height = $item->height ?? 0;
        $weight = $item->weight ?? 0;

        return [
            $item->facebook_id, // id
            $item->name, // title
            strip_tags($item->description) ?? "", // description
            $availability, // availability
            "new", // condition
            $regularPrice, // price
            $salePrice, // sale_price
            $itemUrl, // link
            $mainImageUrl, // image_link
            $additionalImages, // additional_image_link
            config("app.name"), // brand
            $this->getProductType($item), // product_type
            $weight > 0 ? $weight . " kg" : "", // shipping_weight
            "", // shipping
            $length > 0 ? $length . " cm" : "", // shipping_length
            $width > 0 ? $width . " cm" : "", // shipping_width
            $height > 0 ? $height . " cm" : "", // shipping_height
            "", // tax
            "Health & Beauty > Personal Care > Cosmetics > Skin Care", // google_product_category
            $this->getSalePriceDateRange($item), // sale_price_effective_date
        ];
    }
}
