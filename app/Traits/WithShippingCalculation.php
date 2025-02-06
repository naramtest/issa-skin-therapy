<?php

namespace App\Traits;

use App\Enums\Checkout\CartCostType;
use App\Enums\Checkout\ShippingMethodType;
use App\Helpers\DHL\DHLHelper;
use App\Services\Currency\CurrencyHelper;
use App\Services\Shipping\DHL\DHLRateCheckService;
use App\Services\Shipping\ShippingZoneService;
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
            $this->shippingRates = collect();

            if (empty($destination)) {
                return;
            }

            // TODO: change to var in checkout component
            $zoneService = app(ShippingZoneService::class);
            $methods = $zoneService->getAvailableMethodsForCountry(
                $destination["country"]
            );

            $this->freeShippingCoupon($destination["country"], $methods);

            foreach ($methods as $method) {
                // Skip methods that don't meet minimum order requirements
                if (
                    !$method->meetsMinimumOrderRequirement(
                        $this->cartService->getSubtotal()
                    )
                ) {
                    continue;
                }

                $this->shippingRates->push(
                    $zoneService->formatMethodToRate(
                        $method,
                        $this->cartService->itemCount()
                    )
                );
            }

            // Get DHL rates
            $dhlService = app(DHLRateCheckService::class);

            $dhlRates = collect(
                $dhlService->getRates(
                    DHLHelper::weightAndDimensions($this->cartItems),
                    $destination
                )
            );

            // Merge rates

            foreach ($dhlRates as $dhlRate) {
                $this->shippingRates->push($dhlRate);
            }

            // Select first-rate if none selected
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

    public function freeShippingCoupon($country, $methods): void
    {
        $appliedCoupon = $this->cartService->getAppliedCoupon();

        if (!$appliedCoupon) {
            return;
        }

        if (
            !$this->couponService->validateShippingEligibility(
                $appliedCoupon,
                $country
            )
        ) {
            return;
        }

        // Check if the free shipping method already exists using firstWhere as a shorthand.
        $method = $methods->firstWhere(
            "method_type",
            ShippingMethodType::FREE_SHIPPING
        );
        if (
            $method and
            $method->meetsMinimumOrderRequirement(
                $this->cartService->getSubtotal()
            )
        ) {
            return;
        }

        $this->shippingRates->push([
            "service_code" => ShippingMethodType::FREE_SHIPPING->value,
            "service_name" => ShippingMethodType::FREE_SHIPPING,
            "total_price" => 0,
            "currency" => CurrencyHelper::defaultCurrency()->getCode(),
            "guaranteed" => false,
        ]);
    }

    /**
     * @throws Exception
     */
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

    public function updated($property): void
    {
        // Only recalculate shipping when address fields change
        //TODO: don't request new rate if the updated field is the same as the old one
        //TODO: when country updated the state and city should update before asking for rate
        if ($this->isAddressField($property)) {
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
