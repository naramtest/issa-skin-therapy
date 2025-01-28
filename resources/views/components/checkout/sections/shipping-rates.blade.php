{{-- resources/views/components/checkout/sections/shipping-rates.blade.php --}}

@props([
    "shippingRates",
    "selectedShippingRate",
])

<div class="space-y-4">
    @if (! $this->canCalculateShipping)
        <p class="text-gray-500">
            {{ __("store.Please complete your address details to view available shipping             methods") }}
        </p>
    @elseif ($shippingRates->isEmpty())
        <p class="text-gray-500">
            {{ __("store.No shipping methods available for your location") }}
        </p>
    @else
        <div class="space-y-4">
            @foreach ($shippingRates as $rate)
                <label class="block cursor-pointer">
                    <div
                        class="relative flex items-center justify-between rounded-lg"
                    >
                        <div class="flex w-full items-center justify-between">
                            <input
                                type="radio"
                                name="shipping_method"
                                value="{{ $rate["service_code"] }}"
                                wire:model.live="selectedShippingRate"
                                class="text-primary focus:ring-primary h-4 w-4 border-gray-300"
                            />
                            <div class="flex flex-col items-end ps-3 text-sm">
                                <p class="text-gray-900">
                                    {{ $rate["service_name"] }}
                                </p>
                                @if ($rate["total_price"] > 0)
                                    <p class="text-gray-500">
                                        {{ $rate["estimated_days"] ?? __("store.N/A") }}
                                    </p>
                                @endif

                                @if ($rate["total_price"] > 0)
                                    @php
                                        $money = new \Money\Money($rate["total_price"], new \Money\Currency($rate["currency"]));
                                    @endphp

                                    <x-price :money="$money" />
                                @endif

                                @if ($rate["guaranteed"])
                                    <span class="text-xs text-green-600">
                                        {{ __("store.Guaranteed delivery") }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </label>
            @endforeach
        </div>
    @endif

    @error("shipping_method")
        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>
