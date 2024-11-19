<?php

namespace App\Traits\Price;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait HasPricing
{
    /**
     * Get the current active price for the product
     */
    public function getCurrentPrice(): float
    {
        if ($this->isOnSale()) {
            return $this->sale_price;
        }

        return $this->regular_price;
    }

    /**
     * Check if the product is currently on sale
     */
    public function isOnSale(): bool
    {
        if (!$this->sale_price) {
            return false;
        }

        if (!$this->is_sale_scheduled) {
            return true;
        }

        $now = Carbon::now();

        return (!$this->sale_starts_at || $now->gte($this->sale_starts_at)) &&
            (!$this->sale_ends_at || $now->lte($this->sale_ends_at));
    }

    /**
     * Calculate the discount percentage
     */
    public function getDiscountPercentage(): ?float
    {
        if (!$this->isOnSale()) {
            return null;
        }

        return round(
            (($this->regular_price - $this->sale_price) /
                $this->regular_price) *
                100
        );
    }

    /**
     * Schedule a sale for the product
     */
    public function scheduleSale(
        float $salePrice,
        ?Carbon $startDate = null,
        ?Carbon $endDate = null
    ): void {
        $this->update([
            "sale_price" => $salePrice,
            "sale_starts_at" => $startDate,
            "sale_ends_at" => $endDate,
            "is_sale_scheduled" => $startDate || $endDate,
        ]);
    }

    /**
     * Cancel the current sale
     */
    public function cancelSale(): void
    {
        $this->update([
            "sale_price" => null,
            "sale_starts_at" => null,
            "sale_ends_at" => null,
            "is_sale_scheduled" => false,
        ]);
    }

    /**
     * Scope for products currently on sale
     */
    public function scopeOnSale(Builder $query): Builder
    {
        return $query->where(function ($query) {
            $query->whereNotNull("sale_price")->where(function ($query) {
                $query
                    ->where("is_sale_scheduled", false)
                    ->orWhere(function ($query) {
                        $now = Carbon::now();
                        $query
                            ->where("is_sale_scheduled", true)
                            ->where(function ($query) use ($now) {
                                $query
                                    ->whereNull("sale_starts_at")
                                    ->orWhere("sale_starts_at", "<=", $now);
                            })
                            ->where(function ($query) use ($now) {
                                $query
                                    ->whereNull("sale_ends_at")
                                    ->orWhere("sale_ends_at", ">=", $now);
                            });
                    });
            });
        });
    }
}
