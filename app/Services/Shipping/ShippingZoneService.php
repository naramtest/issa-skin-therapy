<?php

namespace App\Services\Shipping;

use App\Models\ShippingMethod;
use App\Models\ShippingZone;
use Illuminate\Support\Collection;

class ShippingZoneService
{
    public function getAvailableMethodsForCountry(
        string $countryCode
    ): Collection {
        // Get all applicable zones for this country
        $zones = ShippingZone::query()
            ->where("is_active", true)
            ->where(function ($query) use ($countryCode) {
                $query
                    ->where("is_all_countries", true)
                    ->orWhereJsonContains("countries", $countryCode);
            })
            ->byPriority()
            ->get();

        if ($zones->isEmpty()) {
            return collect();
        }

        // Initialize collection for methods
        $methods = collect();

        // Process each zone's methods, giving priority to specific zones
        foreach ($zones as $zone) {
            // TODO: check this line I think it need fix
            $zoneMethods = $zone
                ->methods()
                ->where("is_active", true)
                ->orderBy("order")
                ->get();

            foreach ($zoneMethods as $method) {
                // If we already have this method type, and it's from a more specific zone, skip
                $existingMethod = $methods->first(function ($existing) use (
                    $method
                ) {
                    return $existing->method_type === $method->method_type;
                });

                if ($existingMethod) {
                    // If current zone is more specific (not all_countries), replace the method
                    if (!$zone->is_all_countries) {
                        $methods = $methods->reject(function ($existing) use (
                            $method
                        ) {
                            return $existing->method_type ===
                                $method->method_type;
                        });
                        $methods->push($method);
                    }
                } else {
                    // If no existing method of this type, add it
                    $methods->push($method);
                }
            }
        }

        return $methods->sortBy("order");
    }

    public function formatMethodToRate(
        ShippingMethod $method,
        int $itemCount = 1
    ): array {
        $cost = $method->calculateCost($itemCount);

        return [
            "service_code" => $method->method_type->value,
            "service_name" => $method->method_type,
            "total_price" => $cost->getAmount(),
            "currency" => $cost->getCurrency()->getCode(),
            "guaranteed" => false,
        ];
    }
}
