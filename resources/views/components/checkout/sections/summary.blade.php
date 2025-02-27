@props([
    "cartItems",
    "total",
    "subtotal",
    "shippingRates",
    "selectedShippingRate",
    "discount",
])

<div class="relative rounded-2xl bg-[#F5F5F5] p-8">
    <h2 class="mb-6 text-lg font-semibold">
        {{ __("store. Order Summary") }}
    </h2>
    <table class="w-full">
        <thead>
            <tr class="text-lg">
                <th class="pb-4 text-start font-normal text-[#69727d]">
                    {{ __("store.Products") }}
                </th>
                <th class="pb-4 text-end font-normal text-[#69727d]">
                    {{ __("store.Subtotal") }}
                </th>
            </tr>
        </thead>
        <tbody class="">
            @foreach ($cartItems as $item)
                <tr class="text-sm">
                    <td class="py-2">
                        <div class="flex items-center">
                            <h4>
                                {{ $item->getPurchasable()->name }}
                            </h4>
                            <p class="ms-3 text-gray-700">
                                ×
                                {{ $item->getQuantity() }}
                            </p>
                        </div>
                    </td>
                    <td class="py-2 text-end">
                        <x-price :money="$item->getSubtotal()" />
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="divide-y text-[#69727d]">
            <tr>
                <td class="py-4">
                    {{ __("store.Subtotal") }}
                </td>
                <td class="py-4 text-end">
                    <x-price :money="$subtotal" />
                </td>
            </tr>
            @if ($discount)
                <tr>
                    <td class="py-4">{{ __("store.Discount") }}</td>
                    <td class="py-4 text-right text-green-600">
                        -
                        <x-price :money="$discount" />
                    </td>
                </tr>
            @endif

            <tr>
                <td class="py-4">
                    {{ __("store.Shipping") }}
                </td>

                <td class="py-4 text-right text-darkColor">
                    <x-checkout.sections.shipping-rates
                        :shippingRates="$shippingRates"
                        :selectedShippingRate="$selectedShippingRate"
                    />
                </td>
            </tr>
            <tr class="font-medium">
                <td class="py-4">
                    {{ __("store.Total") }}
                </td>
                <td class="py-4 text-end">
                    <x-price :money="$total" />
                </td>
            </tr>
        </tfoot>
    </table>

    <div
        wire:loading.class="!flex"
        class="rounded-inherit absolute inset-0 hidden items-center justify-center overflow-hidden bg-gray-100/70"
    >
        <span class="loader"></span>
    </div>
</div>
