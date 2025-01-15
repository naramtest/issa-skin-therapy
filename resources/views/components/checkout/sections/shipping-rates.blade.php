@props([
    "shippingRates",
    "selectedShippingRate",
])

<div class="space-y-4">
    @if ($shippingRates->isEmpty())
        <p class="text-gray-500">
            No shipping methods available for your location
        </p>
    @else
        <div>
            @foreach ($shippingRates as $rate)
                <div class="text-sm">
                    <p class="text-gray-500">
                        {{ $rate["service_name"] }}
                    </p>
                    <p class="text-gray-500">
                        {{ $rate["estimated_days"] ?? __("store.N/A") }}
                    </p>
                </div>
                <div class="">
                    <bdi>
                        {{ number_format($rate["total_price"], 2) }}
                        {{ $rate["currency"] }}
                    </bdi>
                    @if ($rate["guaranteed"])
                        <span class="text-xs text-green-600">
                            {{ __("store.Guaranteed delivery") }}
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    @error("shipping_method")
        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>
