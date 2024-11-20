<?php

namespace App\Traits\Price;

trait HasBundlePrice
{
    public function calculateTotalPrice(): void
    {
        if (!$this->auto_calculate_price) {
            return;
        }

        $totalRegularPrice = 0;
        $totalCurrentPrice = 0;

        foreach ($this->items as $item) {
            clock($item);
            $product = $item->product;
            $quantity = $item->quantity;

            // Calculate regular price
            $totalRegularPrice += $product->regular_price * $quantity;

            // Calculate current price (considering sales)
            $currentPrice = $product->isOnSale()
                ? $product->sale_price
                : $product->regular_price;
            $totalCurrentPrice += $currentPrice * $quantity;
        }

        // Update bundle prices
        $this->regular_price = $totalRegularPrice;

        // Only set sale price if it's different from regular price
        if ($totalCurrentPrice < $totalRegularPrice) {
            $this->sale_price = $totalCurrentPrice;

            // If any product is on sale, check for the earliest end date
            $earliestEndDate = $this->items
                ->map(function ($item) {
                    return $item->product->sale_ends_at;
                })
                ->filter()
                ->min();

            if ($earliestEndDate) {
                $this->sale_ends_at = $earliestEndDate;
                $this->is_sale_scheduled = true;
            }
        } else {
            $this->sale_price = null;
            $this->sale_ends_at = null;
            $this->is_sale_scheduled = false;
        }
    }
}
