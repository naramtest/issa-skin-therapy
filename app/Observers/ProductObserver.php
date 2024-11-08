<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    public function saving(Product $product): void
    {
        // Ensure regular price is greater than 0
        if ($product->regular_price <= 0) {
            throw new \InvalidArgumentException(
                "Regular price must be greater than 0"
            );
        }

        // Ensure sale price is less than regular price
        if (
            $product->sale_price &&
            $product->sale_price >= $product->regular_price
        ) {
            throw new \InvalidArgumentException(
                "Sale price must be less than regular price"
            );
        }

        // Ensure sale dates are valid
        if (
            $product->sale_starts_at &&
            $product->sale_ends_at &&
            $product->sale_starts_at->gt($product->sale_ends_at)
        ) {
            throw new \InvalidArgumentException(
                "Sale end date must be after start date"
            );
        }

        if ($product->weight && $product->weight <= 0) {
            throw new \InvalidArgumentException(
                "Weight must be greater than 0"
            );
        }

        // Validate country code
        if (
            $product->country_of_origin &&
            strlen($product->country_of_origin) !== 2
        ) {
            throw new \InvalidArgumentException(
                "Country of origin must be an ISO 2 code"
            );
        }
    }

    public function updated(Product $product): void
    {
        // Check for low stock
        if ($product->isLowStock()) {
            // Notify admin about low stock
            // TODO: You'll need to implement this notification
            //            \Notification::route('mail', config('shop.admin_email'))
            //                ->notify(new LowStockNotification($product));
        }
    }
}
