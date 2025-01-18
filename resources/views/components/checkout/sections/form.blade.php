@props([
    "form",
    "billingCities",
    "billingStates",
    "countries",
])

<div class="rounded-2xl border bg-white p-8">
    <h2 class="mb-6 text-xl font-semibold">
        {{ __("store.Information") }}
    </h2>
    {{-- TODO: fix labels --}}
    <!-- Personal Information Section -->
    <div class="space-y-8">
        <div class="grid grid-cols-2 gap-4">
            <x-checkout.input-field
                label="{{ __('store.First Name') }}"
                wire:model="form.billing_first_name"
                place-holder="{{ __('store.First Name') }}"
                required
                field="form.billing_first_name"
                :error="$errors->first('form.billing_first_name')"
            />

            <x-checkout.input-field
                label="{{ __('store.Last Name') }}"
                wire:model="form.billing_last_name"
                required
                place-holder="{{ __('store.Last Name') }}"
                field="form.billing_last_name"
                :error="$errors->first('form.billing_last_name')"
            />
        </div>

        <x-checkout.input-field
            label="Phone"
            wire:model="form.phone"
            required
            place-holder="Phone"
            field="form.phone"
            :error="$errors->first('form.phone')"
            helper-text="{{ __('store.Use english letters') }}"
        />

        <x-checkout.input-field
            label="Email Address"
            wire:model="form.email"
            required
            place-holder="Email Address"
            field="form.email"
            :error="$errors->first('form.email')"
            type="email"
        />

        <!-- Billing Address Section -->
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
                type="billing"
                :billing-cities="$billingCities"
                :billing-states="$billingState"
                :countries="$countries"
            />
        </div>

        <x-checkout.input-field
            label="Street Address"
            wire:model="form.billing_address"
            required
            place-holder="Street Address"
            field="form.billing_address"
            :error="$errors->first('form.billing_address')"
        />

        <x-checkout.input-field
            label="Area/Block"
            wire:model="form.billing_area"
            required
            place-holder="Area and/or Block Number"
            field="form.billing_area"
            :error="$errors->first('form.billing_area')"
        />

        <x-checkout.input-field
            label="Postal Code"
            wire:model="form.billing_postal_code"
            required
            place-holder="{{ __('store.Postal Code') }}"
            field="form.billing_postal_code"
            :error="$errors->first('form.billing_postal_code')"
            helper-text="{{ __('store.Please type') }}"
        />

        <x-checkout.input-field
            label="Building"
            wire:model="form.billing_building"
            required
            place-holder="Building Name/Number"
            field="form.billing_building"
            :error="$errors->first('form.billing_building')"
        />

        <x-checkout.input-field
            label="Flat/Unit"
            wire:model="form.billing_flat"
            required
            place-holder="Flat/Unit Number"
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
                    label="First Name"
                    wire:model="form.shipping_first_name"
                    place-holder="First Name"
                    required
                    field="form.shipping_first_name"
                    :error="$errors->first('form.shipping_first_name')"
                />

                <x-checkout.input-field
                    label="Last Name"
                    wire:model="form.shipping_last_name"
                    required
                    place-holder="Last Name"
                    field="form.shipping_last_name"
                    :error="$errors->first('form.shipping_last_name')"
                />
            </div>

            <x-checkout.select-field
                label="Country / Region"
                wire:model.live="form.shipping_country"
                required
                field="form.shipping_country"
                :options="['AE','Syria']"
                :error="$errors->first('form.shipping_country')"
            />

            <x-checkout.select-field
                label="State / Region"
                wire:model="form.shipping_state"
                required
                field="form.shipping_state"
                :options="['AE','Syria']"
                :error="$errors->first('form.shipping_state')"
            />

            <x-checkout.input-field
                label="City"
                wire:model="form.shipping_city"
                required
                place-holder="City"
                field="form.shipping_city"
                :error="$errors->first('form.shipping_city')"
            />

            <x-checkout.input-field
                label="Street Address"
                wire:model="form.shipping_address"
                required
                place-holder="Street Address"
                field="form.shipping_address"
                :error="$errors->first('form.shipping_address')"
            />

            <x-checkout.input-field
                label="Area/Block"
                wire:model="form.shipping_area"
                required
                place-holder="Area and/or Block Number"
                field="form.shipping_area"
                :error="$errors->first('form.shipping_area')"
            />

            <x-checkout.input-field
                label="Postal Code"
                wire:model="form.shipping_postal_code"
                required
                place-holder="Postal Code"
                field="form.shipping_postal_code"
                :error="$errors->first('form.shipping_postal_code')"
                helper-text="Type *000* if unknown"
            />

            <x-checkout.input-field
                label="Building"
                wire:model="form.shipping_building"
                required
                place-holder="Building Name/Number"
                field="form.shipping_building"
                :error="$errors->first('form.shipping_building')"
            />

            <x-checkout.input-field
                label="Flat/Unit"
                wire:model="form.shipping_flat"
                required
                place-holder="Flat/Unit Number"
                field="form.shipping_flat"
                :error="$errors->first('form.shipping_flat')"
            />
        </div>
    @endif

    <!-- Order Notes -->
    <div class="mt-8">
        <x-checkout.text-area-field
            label="Order Notes"
            wire:model="form.order_notes"
            field="form.order_notes"
            place-holder="Notes about your order, e.g. special notes for delivery."
        />
    </div>
    <!-- Error Messages -->
    @error("order")
        <div class="mt-4 rounded-lg bg-red-50 p-4 text-red-600">
            {{ $message }}
        </div>
    @enderror
</div>
