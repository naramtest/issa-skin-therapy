<?php

namespace App\Traits\Price;

use App\Models\BundleItem;

trait HasBundlePrice
{
    public function calculateAndSavePrices(): void
    {
        if (!$this->auto_calculate_price) {
            return;
        }
        [
            $this->regular_price,
            $this->sale_price,
            $this->sale_ends_at,
            $this->is_sale_scheduled,
        ] = $this->calculateTotalPrice();
    }

    public function calculateTotalPrice(): array
    {
        $totalRegularPrice = $this->calculateTotalRegularPrice();
        $totalCurrentPrice = $this->calculateTotalCurrentPrice();

        $salePrice = null;
        $saleEndsAt = null;
        $isSaleScheduled = false;

        if ($totalCurrentPrice < $totalRegularPrice) {
            $salePrice = $totalCurrentPrice;
            $saleEndsAt = $this->getEarliestSaleEndDate();
            $isSaleScheduled = $saleEndsAt !== null;
        }

        return [$totalRegularPrice, $salePrice, $saleEndsAt, $isSaleScheduled];
    }

    private function calculateTotalRegularPrice(): float
    {
        return $this->items->reduce(function ($total, BundleItem $item) {
            return $total + $item->product->regular_price * $item->quantity;
        }, 0);
    }

    private function calculateTotalCurrentPrice(): float
    {
        return $this->items->reduce(function ($total, $item) {
            $product = $item->product;
            $price = $product->isOnSale()
                ? $product->sale_price
                : $product->regular_price;
            return $total + $price * $item->quantity;
        }, 0);
    }

    private function getEarliestSaleEndDate(): ?string
    {
        return $this->items
            ->map(fn($item) => $item->product->sale_ends_at)
            ->filter()
            ->min();
    }
}
