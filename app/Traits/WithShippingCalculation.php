<?php

namespace App\Traits;

use App\Services\Shipping\DHLShippingService;
use Log;

trait WithShippingCalculation
{
    public function initializeWithShippingCalculation(): void
    {
        $this->shippingRates = collect();
    }

    public function updatedFormBillingCountry($value): void
    {
        if (!$this->form->different_shipping_address) {
            $this->calculateShippingRates();
        }
    }

    public function calculateShippingRates(): void
    {
        $this->loadingRates = true;
        $this->shippingRates = collect();
        $this->selectedShippingRate = null;

        try {
            $destination = $this->getShippingAddress();

            // If we don't have the minimum required address fields, return
            if (empty($destination)) {
                $this->shippingRates = collect();
                return;
            }

            // Add free shipping for US orders
            if ($destination["country"] === "AE") {
                $this->shippingRates->push([
                    "service_code" => "free_shipping",
                    "service_name" => __("store.Free Shipping"),
                    "total_price" => 0,
                    "currency" => config("app.money_currency"),
                    "estimated_days" => "5-7",
                    "guaranteed" => false,
                ]);
            }

            // Get DHL rates
            $dhlService = app(DHLShippingService::class);
            $package = $this->calculatePackageDimensions();
            $dhlRates = collect(
                $dhlService->getRates(
                    $package,
                    $this->getStoreAddress(),
                    $destination
                )
            );

            // Merge DHL rates with free shipping option
            $this->shippingRates = $this->shippingRates->concat($dhlRates);

            // Select first available rate by default
            if ($this->shippingRates->isNotEmpty()) {
                $this->selectedShippingRate = $this->shippingRates->first()[
                    "service_code"
                ];
                $this->updateTotals();
            }
        } catch (\Exception $e) {
            Log::error("Shipping calculation failed", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);

            $this->dispatch(
                "error",
                __("store.Shipping calculation failed. Please try again.")
            );
        } finally {
            $this->loadingRates = false;
        }
    }

    protected function getShippingAddress(): array
    {
        // Check if we have minimum required fields
        if ($this->form->different_shipping_address) {
            if (!$this->form->shipping_country || !$this->form->shipping_city) {
                return [];
            }

            return [
                "country" => $this->form->shipping_country,
                "city" => $this->form->shipping_city,
                "postal_code" => $this->form->shipping_postal_code,
                "address" => $this->form->shipping_address,
                "state" => $this->form->shipping_state,
                "building" => $this->form->shipping_building,
                "flat" => $this->form->shipping_flat,
                "phone" => $this->form->phone,
                "email" => $this->form->email,
                "first_name" => $this->form->shipping_first_name,
                "last_name" => $this->form->shipping_last_name,
            ];
        } else {
            if (!$this->form->billing_country || !$this->form->billing_city) {
                return [];
            }

            return [
                "country" => $this->form->billing_country,
                "city" => $this->form->billing_city,
                "postal_code" => $this->form->billing_postal_code,
                "address" => $this->form->billing_address,
                "state" => $this->form->billing_state,
                "building" => $this->form->billing_building,
                "flat" => $this->form->billing_flat,
                "phone" => $this->form->phone,
                "email" => $this->form->email,
                "first_name" => $this->form->billing_first_name,
                "last_name" => $this->form->billing_last_name,
            ];
        }
    }

    protected function calculatePackageDimensions(): array
    {
        $totalWeight = 0;
        $maxLength = 0;
        $maxWidth = 0;
        $maxHeight = 0;

        foreach ($this->cartItems as $item) {
            $purchasable = $item->getPurchasable();
            $quantity = $item->getQuantity();

            // Ensure we have numeric values
            $weight = floatval($purchasable->weight ?? 0);
            $length = floatval($purchasable->length ?? 0);
            $width = floatval($purchasable->width ?? 0);
            $height = floatval($purchasable->height ?? 0);

            $totalWeight += $weight * $quantity;
            $maxLength = max($maxLength, $length);
            $maxWidth = max($maxWidth, $width);
            $maxHeight = max($maxHeight, $height);
        }

        // Ensure minimum values to avoid API errors
        return [
            "weight" => max($totalWeight, 0.1), // Minimum 100g
            "length" => max($maxLength, 1), // Minimum 1cm
            "width" => max($maxWidth, 1), // Minimum 1cm
            "height" => max($maxHeight, 1), // Minimum 1cm
        ];
    }

    protected function getStoreAddress(): array
    {
        return [
            "country" => config("store.address.country"),
            "city" => config("store.address.city"),
            "postal_code" => config("store.address.postal_code"),
            "address" => config("store.address.address"),
            "state" => config("store.address.state"),
            "phone" => config("store.address.phone"),
            "email" => config("store.address.email"),
            "first_name" => config("store.name"),
            "last_name" => "",
        ];
    }

    public function updateTotals(): void
    {
        if ($this->selectedShippingRate) {
            $rate = $this->shippingRates->firstWhere(
                "service_code",
                $this->selectedShippingRate
            );
            if ($rate) {
                // Update order shipping cost and total
                $this->shippingCost = $rate["total_price"];
                // Recalculate total with shipping
                //                $this->calculateTotal();
            }
        }
    }

    public function updatedFormBillingCity($value): void
    {
        if (!$this->form->different_shipping_address) {
            $this->calculateShippingRates();
        }
    }

    public function updatedFormBillingPostalCode($value): void
    {
        if (!$this->form->different_shipping_address) {
            $this->calculateShippingRates();
        }
    }

    public function updatedFormShippingCountry($value): void
    {
        if ($this->form->different_shipping_address) {
            $this->calculateShippingRates();
        }
    }

    public function updatedFormShippingCity($value): void
    {
        if ($this->form->different_shipping_address) {
            $this->calculateShippingRates();
        }
    }

    public function updatedFormShippingPostalCode($value): void
    {
        if ($this->form->different_shipping_address) {
            $this->calculateShippingRates();
        }
    }

    public function updatedFormDifferentShippingAddress($value): void
    {
        $this->calculateShippingRates();
    }
}
