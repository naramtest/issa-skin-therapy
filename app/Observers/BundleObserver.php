<?php

namespace App\Observers;

use App\Models\Bundle;
use App\Models\Product;
use App\Services\Product\ProductCacheService;
use InvalidArgumentException;

readonly class BundleObserver
{
    public function __construct(
        private ProductCacheService $productCacheService
    ) {
    }

    public function saving(Bundle $bundle): void
    {
        // Validate regular price
        //        TODO: reactivate this after making the regular price update automatically in Filament Resource
        //        if ($bundle->regular_price <= 0) {
        //            throw new InvalidArgumentException(
        //                "Regular price must be greater than 0"
        //            );
        //        }

        // Validate sale price
        if (
            $bundle->sale_price &&
            $bundle->sale_price >= $bundle->regular_price
        ) {
            throw new InvalidArgumentException(
                "Sale price must be less than regular price"
            );
        }

        // Validate sale dates
        if (
            $bundle->sale_starts_at &&
            $bundle->sale_ends_at &&
            $bundle->sale_starts_at->gt($bundle->sale_ends_at)
        ) {
            throw new InvalidArgumentException(
                "Sale end date must be after start date"
            );
        }

        // Validate total weight if shipping is required
        //        if ($bundle->requires_shipping && !$bundle->weight) {
        //            // Auto-calculate weight from items if not set
        //            $totalWeight = $bundle->items->sum(function ($item) {
        //                return $item->product->weight * $item->quantity;
        //            });
        //            $bundle->weight = $totalWeight;
        //        }
    }

    public function saved(Product $product): void
    {
        $this->productCacheService->clearAllBundlesCache();
    }

    public function deleted(Product $product): void
    {
        $this->productCacheService->clearAllBundlesCache();
    }
}
