{{-- resources/views/components/checkout/sections/form.blade.php --}}

@props([
    "form",
    "billingCities",
    "billingStates",
    "shippingCities",
    "shippingStates",
    "countries",
])

<div class="rounded-2xl border bg-white p-8">
    <h2 class="mb-6 text-xl font-semibold">
        {{ __("store.Information") }}
    </h2>
    <!-- Personal Information Section -->
    <div class="space-y-8">
        <div class="grid grid-cols-2 gap-4">
            <x-checkout.input-field
                label="{{ __('store.First Name') }}"
                wire:model.blur="form.billing_first_name"
                place-holder="{{ __('store.First Name') }}"
                required
                field="form.billing_first_name"
                :error="$errors->first('form.billing_first_name')"
            />

            <x-checkout.input-field
                label="{{ __('store.Last Name') }}"
                wire:model.blur="form.billing_last_name"
                required
                place-holder="{{ __('store.Last Name') }}"
                field="form.billing_last_name"
                :error="$errors->first('form.billing_last_name')"
            />
        </div>
        {{-- TODO: phone number --}}
        <x-checkout.input-field
            label="{{ __('dashboard.info.phone') }}"
            wire:model.blur="form.phone"
            required
            place-holder="{{ __('dashboard.info.phone') }}"
            field="form.phone"
            :error="$errors->first('form.phone')"
            helper-text="{{ __('store.Use english letters') }}"
        />

        <x-checkout.input-field
            label="{{ __('store.Email Address') }}"
            wire:model.blur="form.email"
            required
            place-holder="{{ __('store.Email Address') }}"
            field="form.email"
            :error="$errors->first('form.email')"
            type="email"
        />

        <!-- Billing Address Section -->
        {{-- TODO: add loading --}}
        <div
            x-data="{ loading: false }"
            x-on:loading.window="loading = $event.detail.loading"
            class="relative"
        >
            <!-- Loading overlay -->
            <div
                x-show="loading"
                class="absolute inset-0 z-10 flex items-center justify-center bg-white bg-opacity-75"
            >
                <div
                    class="h-8 w-8 animate-spin rounded-full border-b-2 border-gray-900"
                ></div>
            </div>

            <!-- Location selects -->
            <x-checkout.sections.location-selects
                prefix="billing"
                :cities="$billingCities"
                :states="$billingState"
                :countries="$countries"
                :form="$form"
            />
        </div>

        <x-checkout.input-field
            label="{{ __('store.Postal Code') }}"
            wire:model.blur="form.billing_postal_code"
            required
            place-holder="{{ __('store.Postal Code') }}"
            field="form.billing_postal_code"
            :error="$errors->first('form.billing_postal_code')"
            helper-text="{{ __('store.Please type') }}"
        />

        <x-checkout.input-field
            label="{{ __('store.Street Address') }}"
            wire:model="form.billing_address"
            required
            place-holder="{{ __('store.Street Address') }}"
            field="form.billing_address"
            :error="$errors->first('form.billing_address')"
        />

        <x-checkout.input-field
            label="{{ __('store.Area/Block') }}"
            wire:model="form.billing_area"
            required
            place-holder="{{ __('store.Area and/or Block Number') }}"
            field="form.billing_area"
            :error="$errors->first('form.billing_area')"
        />

        <x-checkout.input-field
            label="{{ __('store.Building') }}"
            wire:model="form.billing_building"
            required
            place-holder="{{ __('store.Building Name/Number') }}"
            field="form.billing_building"
            :error="$errors->first('form.billing_building')"
        />

        <x-checkout.input-field
            label="{{ __('store.Flat/Unit') }}"
            wire:model="form.billing_flat"
            required
            place-holder="{{ __('store.Flat/Unit Number') }}"
            field="form.billing_flat"
            :error="$errors->first('form.billing_flat')"
        />
    </div>

    <!-- Additional Options -->
    <div class="mt-8 space-y-4">
        @guest
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    wire:model="form.create_account"
                    class="rounded border-gray-300"
                />
                <span class="text-sm">
                    {{ __("store.Create an account?") }}
                </span>
            </label>
        @endguest

        <label class="flex items-center gap-2">
            <input
                type="checkbox"
                wire:model.live="form.different_shipping_address"
                class="rounded border-gray-300"
            />
            <span class="text-sm">
                {{ __("store.Ship to a different address?") }}
            </span>
        </label>
    </div>

    <!-- Shipping Address Section (Conditional) -->
    @if ($form->different_shipping_address)
        <div class="mt-8 space-y-6 border-t pt-6">
            <div class="grid grid-cols-2 gap-4">
                <x-checkout.input-field
                    label="{{ __('store.First Name') }}"
                    wire:model="form.shipping_first_name"
                    place-holder="{{ __('store.First Name') }}"
                    required
                    field="form.shipping_first_name"
                    :error="$errors->first('form.shipping_first_name')"
                />

                <x-checkout.input-field
                    label="{{ __('store.Last Name') }}"
                    wire:model="form.shipping_last_name"
                    required
                    place-holder="{{ __('store.Last Name') }}"
                    field="form.shipping_last_name"
                    :error="$errors->first('form.shipping_last_name')"
                />
            </div>

            <!-- Location selects -->
            <x-checkout.sections.location-selects
                prefix="shipping"
                :cities="$shippingCities"
                :states="$shippingStates"
                :countries="$countries"
                :form="$form"
            />

            <x-checkout.input-field
                label="{{ __('store.Street Address') }}"
                wire:model="form.shipping_address"
                required
                place-holder="{{ __('store.Street Address') }}"
                field="form.shipping_address"
                :error="$errors->first('form.shipping_address')"
            />

            <x-checkout.input-field
                label="{{ __('store.Area/Block') }}"
                wire:model="form.shipping_area"
                required
                place-holder="{{ __('store.Area and/or Block Number') }}"
                field="form.shipping_area"
                :error="$errors->first('form.shipping_area')"
            />

            <x-checkout.input-field
                label="{{ __('store.Postal Code') }}"
                wire:model.blur="form.shipping_postal_code"
                required
                place-holder="{{ __('store.Postal Code') }}"
                field="form.shipping_postal_code"
                :error="$errors->first('form.shipping_postal_code')"
                helper-text="Type *000* if unknown"
            />

            <x-checkout.input-field
                label="{{ __('store.Building') }}"
                wire:model="form.shipping_building"
                required
                place-holder="{{ __('store.Building Name/Number') }}"
                field="form.shipping_building"
                :error="$errors->first('form.shipping_building')"
            />

            <x-checkout.input-field
                label="{{ __('store.Flat/Unit') }}"
                wire:model="form.shipping_flat"
                required
                place-holder="{{ __('store.Flat/Unit Number') }}"
                field="form.shipping_flat"
                :error="$errors->first('form.shipping_flat')"
            />
        </div>
    @endif

    <!-- Order Notes -->
    <div class="mt-8">
        <x-checkout.text-area-field
            label="{{ __('store.Order Notes') }}"
            wire:model="form.order_notes"
            field="form.order_notes"
            place-holder="{{ __('store.Notes about your order, e.g. special notes for delivery.') }}"
        />
    </div>
    <!-- Error Messages -->
    @error("order")
        <div class="mt-4 rounded-lg bg-red-50 p-4 text-red-600">
            {{ $message }}
        </div>
    @enderror
</div>
