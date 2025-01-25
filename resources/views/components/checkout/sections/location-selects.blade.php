{{-- resources/views/components/checkout/sections/location-selects.blade.php --}}

@props([
    "prefix",
    "cities",
    "states",
    "countries",
    "form",
])

<div class="space-y-6">
    <x-checkout.select
        label="{{ __('store.Country / Region') }}"
        wire:model.live="form.{{ $prefix }}_country"
        required
        field="form.{{ $prefix }}_country"
    >
        <option value="">{{ __("store.Select Country") }}</option>
        @foreach ($countries as $country)
            <option
                @selected($form->{$prefix . "_country"} == $country->iso2)
                value="{{ $country->iso2 }}"
            >
                {{ $country->name }}
            </option>
        @endforeach
    </x-checkout.select>

    <x-checkout.select
        label="{{ __('store.State / County') }}"
        wire:model.live="form.{{ $prefix }}_state"
        required
        field="form.{{ $prefix }}_state"
        :disabled="$states->isEmpty()"
    >
        <option value="">{{ __("store.Select State") }}</option>
        @foreach ($states as $state)
            <option
                @selected($form->{$prefix . "_state"} == $state->id)
                value="{{ $state->id }}"
            >
                {{ $state->name }}
            </option>
        @endforeach
    </x-checkout.select>

    <x-checkout.select
        label="{{ __('store.Town / City') }}"
        wire:model.live="form.{{ $prefix }}_city"
        required
        field="form.{{ $prefix }}_city"
        :disabled="$cities->isEmpty()"
    >
        <option value="">{{ __("store.Select City") }}</option>
        @foreach ($cities as $city)
            <option
                @selected($form->{$prefix . "_city"} == $city->name)
                value="{{ $city->name }}"
            >
                {{ $city->name }}
            </option>
        @endforeach
    </x-checkout.select>
</div>
