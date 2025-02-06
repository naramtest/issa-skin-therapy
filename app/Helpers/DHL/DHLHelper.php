<?php

namespace App\Helpers\DHL;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class DHLHelper
{
    public static function getDate(Carbon $plannedDate): string
    {
        return $plannedDate->format("Y-m-d\TH:i:s") .
            " GMT" .
            now()->format("P");
    }

    public static function weightAndDimensions(array|Collection $items): array
    {
        $calculatePackageDimensions = self::calculatePackageDimensions($items);
        return [
            "weight" => floatval($calculatePackageDimensions["weight"]),

            "dimensions" => [
                "length" => floatval($calculatePackageDimensions["length"]),
                "width" => floatval($calculatePackageDimensions["width"]),
                "height" => floatval($calculatePackageDimensions["height"]),
            ],
        ];
    }

    protected static function calculatePackageDimensions($items): array
    {
        // Basic implementation - you might want to improve this based on your needs
        $maxLength = 0;
        $maxWidth = 0;
        $maxHeight = 0;
        $totalWeight = 0;

        foreach ($items as $item) {
            $purchasable = $item->purchasable;
            $maxLength = max($maxLength, $purchasable->length ?? 0);
            $maxWidth = max($maxWidth, $purchasable->width ?? 0);
            $maxHeight = max($maxHeight, $purchasable->height ?? 0);
            $totalWeight += ($purchasable->weight ?? 0) * $item->quantity;
        }

        return [
            "length" => floatval(max($maxLength, 1)),
            "width" => floatval(max($maxWidth, 1)),
            "height" => floatval(max($maxHeight, 1)),
            "weight" => floatval(max($totalWeight, 0.1)), // Minimum 100g
        ];
    }
}
