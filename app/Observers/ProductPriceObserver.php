<?php

namespace App\Observers;

use App\Models\Product;

class ProductPriceObserver
{
    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $priceFields = [
            "regular_price",
            "sale_price",
            "sale_starts_at",
            "sale_ends_at",
        ];

        // Check if any price-related fields were modified
        if ($product->wasChanged($priceFields)) {
            // Get all bundles with auto-calculate enabled that contain this product
            $bundles = $product->autoCalculatedBundles;

            foreach ($bundles as $bundle) {
                $bundle->calculateTotalPrice();
                $bundle->save();
            }
        }
    }
}
