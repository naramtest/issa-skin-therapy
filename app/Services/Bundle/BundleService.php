<?php

namespace App\Services\Bundle;

use App\Enums\ProductStatus;
use App\Models\Bundle;
use Carbon\Carbon;

class BundleService
{
    public function handleSaving(Bundle $bundle): void
    {
        if (
            $bundle->status === ProductStatus::PUBLISHED &&
            empty($bundle->published_at)
        ) {
            $bundle->published_at = Carbon::now();
        }

        // Clear published_at when status changes to draft
        if (
            $bundle->status === ProductStatus::DRAFT &&
            $bundle->getOriginal("status") === ProductStatus::PUBLISHED->value
        ) {
            $bundle->published_at = null;
        }

        // Auto calculate price if enabled
        if ($bundle->auto_calculate_price and count($bundle->items)) {
            $bundle->calculateAndSavePrices();
        }

        $bundle->stock_status = $bundle->inventory()->determineStockStatus();
    }
}
