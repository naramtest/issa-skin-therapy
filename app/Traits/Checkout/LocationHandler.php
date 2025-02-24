<?php

namespace App\Traits\Checkout;

use App\Services\LocationService;
use Illuminate\Support\Collection;

trait LocationHandler
{
    //    TODO: 1- fix select style , translate labels , Loading
    //    TODO: 2- Translate to arabic
    //    TODO: 3- set state , city based on  register user address
    public Collection $countries;
    public Collection $billingStates;
    public Collection $billingCities;
    public Collection $shippingStates;
    public Collection $shippingCities;

    public function setupLocationHandler(): void
    {
        $this->locationService = app(LocationService::class);
        $this->countries = $this->locationService->getCountries();
        $this->billingStates = collect();
        $this->billingCities = collect();
        $this->shippingStates = collect();
        $this->shippingCities = collect();

        // Initialize states and cities if form has saved values
        if ($this->form->billing_country) {
            $this->loadBillingStates();
            if ($this->form->billing_state) {
                $this->loadBillingCities();
            }
        }

        if ($this->form->shipping_country) {
            $this->loadShippingStates();
            if ($this->form->shipping_state) {
                $this->loadShippingCities();
            }
        }
    }

    protected function loadBillingStates(): void
    {
        $country = $this->countries->firstWhere(
            "iso2",
            $this->form->billing_country
        );
        if ($country) {
            $this->billingStates = $this->locationService->getStates(
                $country->id
            );
        }
    }

    protected function loadBillingCities(): void
    {
        if ($this->form->billing_state) {
            $cities = $this->locationService->getCities(
                $this->form->billing_state
            );
            $this->billingCities = $cities;

            // If we only have one virtual city, automatically select it
            //            if (
            //                $cities->count() === 1 &&
            //                ($cities->first()?->name ?? $this->form->billing_state)
            //            ) {
            //                $this->form->billing_city = $cities->first()->name;
            //            }
        }
    }

    protected function loadShippingStates(): void
    {
        $country = $this->countries->firstWhere(
            "iso2",
            $this->form->shipping_country
        );
        if ($country) {
            $this->shippingStates = $this->locationService->getStates(
                $country->id
            );
        }
    }

    protected function loadShippingCities(): void
    {
        if ($this->form->shipping_state) {
            $cities = $this->locationService->getCities(
                $this->form->shipping_state
            );
            $this->shippingCities = $cities;

            // If we only have one virtual city, automatically select it
            if (
                $cities->count() === 1 &&
                ($cities->first()?->id ?? $this->form->shipping_state)
            ) {
                $this->form->shipping_city = $cities->first()->id;
            }
        }
    }

    public function updatedFormBillingCountry($value): void
    {
        if ($value) {
            $this->loadBillingStates();
        } else {
            $this->billingStates = collect();
        }
        if ($value === "AE") {
            $this->form->billing_postal_code = "00000";
        }
        $this->form->billing_state = "";
        $this->form->billing_city = "";
        $this->billingCities = collect();

        if (!$this->form->different_shipping_address) {
            $this->form->shipping_country = $value;
            $this->updatedFormShippingCountry($value);
        }
        $this->dispatch("locationUpdated");
    }

    public function updatedFormShippingCountry($value): void
    {
        if ($value) {
            $this->loadShippingStates();
        } else {
            $this->shippingStates = collect();
        }

        $this->form->shipping_state = "";
        $this->form->shipping_city = "";
        $this->shippingCities = collect();
        $this->dispatch("locationUpdated");
    }

    public function updatedFormBillingState($value): void
    {
        if ($value) {
            $this->loadBillingCities();
        } else {
            $this->billingCities = collect();
        }
        $this->form->billing_city = "";

        if (!$this->form->different_shipping_address) {
            $this->form->shipping_state = $value;
            $this->updatedFormShippingState($value);
        }
        $this->dispatch("locationUpdated");
    }

    public function updatedFormShippingState($value): void
    {
        if ($value) {
            $this->loadShippingCities();
        } else {
            $this->shippingCities = collect();
        }

        $this->form->shipping_city = "";
    }
}
