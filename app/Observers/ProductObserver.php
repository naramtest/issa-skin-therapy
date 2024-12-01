<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\Bundle\BundleQuantityUpdateService;
use App\Services\Product\ProductCacheService;
use InvalidArgumentException;

readonly class ProductObserver
{
    public function __construct(
        private ProductCacheService $productCacheService
    )
    {
    }

    public function saving(Product $product): void
    {
        // Ensure regular price is greater than 0
        if ($product->regular_price <= 0) {
            throw new InvalidArgumentException(
                "Regular price must be greater than 0"
            );
        }

        // Ensure sale price is less than regular price
        if (
            $product->sale_price &&
            $product->sale_price >= $product->regular_price
        ) {
            throw new InvalidArgumentException(
                "Sale price must be less than regular price"
            );
        }

        // Ensure sale dates are valid
        if (
            $product->sale_starts_at &&
            $product->sale_ends_at &&
            $product->sale_starts_at->gt($product->sale_ends_at)
        ) {
            throw new InvalidArgumentException(
                "Sale end date must be after start date"
            );
        }

        if ($product->weight && $product->weight <= 0) {
            throw new InvalidArgumentException("Weight must be greater than 0");
        }

        // Validate country code
        if (
            $product->country_of_origin &&
            strlen($product->country_of_origin) !== 2
        ) {
            throw new InvalidArgumentException(
                "Country of origin must be an ISO 2 code"
            );
        }
    }

    public function updated(Product $product): void
    {
        $priceFields = [
            "regular_price",
            "sale_price",
            "sale_starts_at",
            "sale_ends_at",
        ];

        if ($product->wasChanged($priceFields)) {
            $bundles = $product
                ->bundles()
                ->where("auto_calculate_price", true)
                ->get();

            foreach ($bundles as $bundle) {
                $bundle->calculateAndSavePrices();
                $bundle->save();
            }
        }

        $stockFields = [
            "quantity",
            "stock_status",
            "track_quantity",
            "allow_backorders",
        ];

        if ($product->wasChanged($stockFields)) {
            $bundles = $product->bundles;
            foreach ($bundles as $bundle) {
                app(BundleQuantityUpdateService::class)->syncBundleStock(
                    $bundle
                );
            }
        }
    }

    public function saved(Product $product): void
    {
        $this->productCacheService->clearAllProductCache();
        $this->productCacheService->clearFeaturedProductCache();
    }

    public function deleted(Product $product): void
    {
        $this->productCacheService->clearAllProductCache();
        $this->productCacheService->clearFeaturedProductCache();
        
    }
}
