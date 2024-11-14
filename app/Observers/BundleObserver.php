<?php

namespace App\Observers;

use App\Models\Bundle;
use InvalidArgumentException;

class BundleObserver
{
    public function saving(Bundle $bundle): void
    {
        // Validate regular price
        if ($bundle->regular_price <= 0) {
            throw new InvalidArgumentException(
                "Regular price must be greater than 0"
            );
        }

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

        // Ensure bundle has items
        if ($bundle->items()->count() === 0) {
            throw new InvalidArgumentException(
                "Bundle must contain at least one product"
            );
        }

        // Validate total weight if shipping is required
        if ($bundle->requires_shipping && !$bundle->weight) {
            // Auto-calculate weight from items if not set
            $totalWeight = $bundle->items->sum(function ($item) {
                return $item->product->weight * $item->quantity;
            });
            $bundle->weight = $totalWeight;
        }
    }
}
