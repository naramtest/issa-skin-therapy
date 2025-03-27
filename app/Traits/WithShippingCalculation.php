<?php

namespace App\Traits;

use App\Enums\Checkout\CartCostType;
use App\Enums\Checkout\ShippingMethodType;
use App\Services\Currency\CurrencyHelper;
use App\Services\Shipping\DHL\DHLRateCheckService;
use App\Services\Shipping\ShippingZoneService;
use Exception;
use Log;
use Money\Currency;
use Money\Money;

trait WithShippingCalculation
{
    public bool $canCalculateShipping = false;
    public bool $loadingRates = false;

    protected ShippingZoneService $shippingZoneService;

    public function initializeWithShippingCalculation(
        ShippingZoneService $shippingZoneService
    ): void {
        $this->shippingZoneService = $shippingZoneService;
    }

    public function updatedSelectedShippingRate($value): void
    {
        // Don't recalculate rates when only changing selected rate
        $this->updateTotals();
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

    protected function checkAddressCompleteness(): void
    {
        $this->canCalculateShipping = $this->hasCompleteAddress();
        if ($this->canCalculateShipping) {
            $this->calculateShippingRates();
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

            $methods = $this->shippingZoneService->getAvailableMethodsForCountry(
                $destination["country"]
            );

            $hasFreeShipping = $this->freeShippingCoupon(
                $destination["country"],
                $methods
            );
            if ($hasFreeShipping) {
                $this->shippingRates->push([
                    "service_code" => ShippingMethodType::FREE_SHIPPING->value,
                    "service_name" => ShippingMethodType::FREE_SHIPPING,
                    "total_price" => 0,
                    "currency" => CurrencyHelper::defaultCurrency()->getCode(),
                    "guaranteed" => false,
                ]);
            }

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
                    $this->shippingZoneService->formatMethodToRate(
                        $method,
                        $this->cartService->itemCount()
                    )
                );
            }

            // Get DHL rates
            $dhlService = app(DHLRateCheckService::class);

            $dhlRates = collect($dhlService->getRates($destination));
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
}
