<?php

namespace App\Exports;

use App\Helpers\Media\ImageGetter;
use App\Models\Bundle;
use App\Models\Product;
use App\Services\Currency\CurrencyHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

abstract class BaseCatalogExport implements
    FromCollection,
    WithHeadings,
    WithMapping
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

    /**
     * Get column headings - each export should define its own headers
     */
    abstract public function headings(): array;

    /**
     * Convert each product to an array based on headings - each export should define its own mapping
     */
    abstract public function map($item): array;

    /**
     * Common helper functions for all catalog exports
     */

    /**
     * Get the detail URL for the item
     */
    protected function getItemUrl(Product|Bundle $item): string
    {
        return $this->isProduct($item)
            ? route("product.show", ["product" => $item->slug])
            : route("product.bundle", ["bundle" => $item->slug]);
    }

    /**
     * Determine if an item is a product or bundle
     */
    protected function isProduct(Model $item): bool
    {
        return $item instanceof Product;
    }

    /**
     * Get the main image URL for the item
     */
    protected function getMainImageUrl(Product|Bundle $item): string
    {
        return ImageGetter::getMediaUrl($item);
    }

    /**
     * Get additional images for the item
     */
    protected function getAdditionalImages(Product|Bundle $item): ?string
    {
        return ImageGetter::getGalleryImages($item);
    }

    /**
     * Get the product type or category
     */
    protected function getProductType(Product|Bundle $item): string
    {
        if ($this->isProduct($item)) {
            return $item->categories->isNotEmpty()
                ? $item->categories->first()->name
                : "Skin Care";
        }

        return "Bundle";
    }

    /**
     * Get the availability status
     */
    protected function getAvailabilityStatus(Product|Bundle $item): string
    {
        return $item->inventory()->getCurrentQuantity() > 0
            ? "in stock"
            : "out of stock";
    }

    /**
     * Format a price for catalog output
     */
    protected function formatPrice(?float $price): ?string
    {
        if ($price === null) {
            return null;
        }

        return CurrencyHelper::faceBookCurrency($price);
    }

    /**
     * Get the effective sale price date range
     */
    protected function getSalePriceDateRange(Product|Bundle $item): ?string
    {
        if (
            $this->isOnSale($item) &&
            $item->sale_starts_at &&
            $item->sale_ends_at
        ) {
            return $item->sale_starts_at->format("Y-m-d\TH:i:sP") .
                "/" .
                $item->sale_ends_at->format("Y-m-d\TH:i:sP");
        }

        return null;
    }

    /**
     * Check if the item is on sale
     */
    protected function isOnSale(Product|Bundle $item): bool
    {
        return $item->isOnSale();
    }
}
