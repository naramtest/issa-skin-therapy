<?php

namespace App\Services\Shipping\DHL;

use App\Services\Cart\CartService;
use Exception;
use Illuminate\Support\Facades\Log;

class DHLRateCheckService
{
    protected DHLExpressCommerceService $dhlCommerceService;

    public function __construct(DHLExpressCommerceService $dhlCommerceService)
    {
        $this->dhlCommerceService = $dhlCommerceService;
    }

    public function getRates(array $package, array $destination): array
    {
        try {
            if (!$this->validateDestination($destination)) {
                Log::warning("Invalid destination data for DHL rate check", [
                    "destination" => $destination,
                ]);
                return [];
            }

            // Get rates from DHL Express Commerce API
            return $this->dhlCommerceService->getRates(
                $this->getCartItems(),
                $destination
            );
        } catch (Exception $e) {
            Log::error("DHL Rate Check Error", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);
            return [];
        }
    }

    protected function validateDestination(array $destination): bool
    {
        $requiredFields = ["country", "city", "postal_code", "address"];

        foreach ($requiredFields as $field) {
            if (empty($destination[$field])) {
                return false;
            }
        }

        return true;
    }

    protected function getCartItems(): array
    {
        // Get cart items from your cart service
        return app(CartService::class)->getItems();
    }
}
