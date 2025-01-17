<?php

namespace App\Traits;

use App\Enums\Checkout\CartCostType;
use App\Services\Shipping\DHLShippingService;
use Exception;
use Log;
use Money\Currency;
use Money\Money;

trait WithShippingCalculation
{
    //  TODO: Organize
    public bool $canCalculateShipping = false;

    public function initializeWithShippingCalculation(): void
    {
        $this->shippingRates = collect();
        // Check address completeness on initialization
        $this->checkAddressCompleteness();
    }

    protected function checkAddressCompleteness(): void
    {
        $this->canCalculateShipping = $this->hasCompleteAddress();

        if ($this->canCalculateShipping) {
            $this->calculateShippingRates();
        } else {
            $this->shippingRates = collect();
            $this->selectedShippingRate = null;
        }
    }

    protected function hasCompleteAddress(): bool
    {
        if ($this->form->different_shipping_address) {
            return !empty($this->form->shipping_country) &&
                !empty($this->form->shipping_state) &&
                !empty($this->form->shipping_city) &&
                !empty($this->form->shipping_postal_code);
        }

        return !empty($this->form->billing_country) &&
            !empty($this->form->billing_state) &&
            !empty($this->form->billing_city) &&
            !empty($this->form->billing_postal_code);
    }

    public function calculateShippingRates(): void
    {
        $this->loadingRates = true;

        try {
            $destination = $this->getShippingAddress();

            if (empty($destination)) {
                $this->shippingRates = collect();
                return;
            }

            $rates = collect();

            // Add free shipping for UAE
            if ($destination["country"] === "AE") {
                $rates->push([
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

            // Merge rates
            $this->shippingRates = $rates->concat($dhlRates);

            // Select first rate if none selected
            if (
                $this->shippingRates->isNotEmpty() &&
                empty($this->selectedShippingRate)
            ) {
                $this->selectedShippingRate = $this->shippingRates->first()[
                    "service_code"
                ];
                $this->updateTotals();
            }
        } catch (Exception $e) {
            Log::error("Shipping calculation failed", [
                "error" => $e->getMessage(),
                "trace" => $e->getTraceAsString(),
            ]);

            $this->dispatch(
                "error",
                __("store.Shipping calculation failed. Please try again.")
            );
            $this->shippingRates = collect();
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

    protected function updateTotals(): void
    {
        if ($this->selectedShippingRate && $this->shippingRates->isNotEmpty()) {
            $rate = $this->shippingRates->firstWhere(
                "service_code",
                $this->selectedShippingRate
            );
            if ($rate) {
                $this->shippingCost = $rate["total_price"];
                $this->cartService->addCost(
                    CartCostType::SHIPPING,
                    new Money(
                        $rate["total_price"],
                        new Currency($rate["currency"])
                    )
                );
                unset($this->total);
            }
        }
    }

    public function updatedSelectedShippingRate($value): void
    {
        // Don't recalculate rates when only changing selected rate
        $this->updateTotals();
    }

    public function updated($field): void
    {
        // Only recalculate shipping when address fields change
        if ($this->isAddressField($field)) {
            $this->checkAddressCompleteness();
        }
    }

    protected function isAddressField(string $field): bool
    {
        $addressFields = [
            "form.billing_country",
            "form.billing_state",
            "form.billing_city",
            "form.billing_postal_code",
            "form.shipping_country",
            "form.shipping_state",
            "form.shipping_city",
            "form.shipping_postal_code",
            "form.different_shipping_address",
        ];

        return in_array($field, $addressFields);
    }
}
