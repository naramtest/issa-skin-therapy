<div class="mx-auto max-w-3xl">
    {{ __("dashboard.Order") }} {{ $this->order->order_number }}
    {{ __("store.was placed on") }} {{ $this->order->created_at }}
    {{ __("store.and is currently") }} {{ $this->order->status->getLabel() }}.
    <!-- Order Details -->
    <h1 class="my-8 text-2xl font-medium">{{ __("store.Order details") }}</h1>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <table class="w-full">
            <thead>
                <tr class="bg-[#fafafa]">
                    <th class="border p-4 text-start">
                        {{ __("dashboard.Product") }}
                    </th>
                    <th class="border p-4 text-start">
                        {{ __("store.Total") }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <!-- Products -->
                @foreach ($this->order->items as $item)
                    <x-tracking.product-item :item="$item" />
                @endforeach

                <!-- Summary -->
                <tr class="border-b">
                    <td class="border p-4 font-medium">
                        {{ __("store.Subtotal") }}:
                    </td>
                    <td class="border p-4">
                        <x-price :money="$this->order->getMoneySubtotal()" />
                    </td>
                </tr>
                <tr class="border-b">
                    <td class="border p-4 font-medium">
                        {{ __("store.Shipping") }}:
                    </td>
                    <td class="border p-4">
                        <div>
                            <x-price
                                :money="$this->order->money_shipping_cost"
                            />
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ __("store.via") }}
                            {{ $this->order->shipping_method->getLabel() }}
                        </div>
                    </td>
                </tr>
                <tr class="border-b">
                    <td class="border p-4 font-medium">
                        {{ __("store.Payment method") }}:
                    </td>
                    <td class="border p-4">
                        {{ strtoupper($this->order->payment_method_details["brand"] ?? "") }}
                        @if (isset($this->order->payment_method_details["last4"]))
                            {{ __("store.ENDING IN") }}
                            {{ $this->order->payment_method_details["last4"] }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="border p-4 font-medium">
                        {{ __("store.Total") }}:
                    </td>
                    <td class="border p-4 font-medium">
                        <x-price :money="$this->order->getMoneyTotal()" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Order Again Button -->
    <div class="mb-12 mt-6 w-fit">
        <a href="{{ route("shop.index") }}">
            <x-general.button-black-animation class="rounded-md !py-2 px-4">
                <span class="relative z-[10]">
                    {{ __("store.Order again") }}
                </span>
            </x-general.button-black-animation>
        </a>
    </div>
    @php
        $shippingOrder = $this->order->shippingOrder;
    @endphp

    <!-- Tracking Information -->
    @if ($shippingOrder and $shippingOrder->tracking_number)
        <h2 class="mb-8 text-2xl font-medium">
            {{ __("store.Tracking Information") }}
        </h2>

        <div class="overflow-hidden rounded-xl bg-white shadow-sm">
            <!-- On larger screens show table, on mobile show cards -->
            <div class="hidden md:block">
                <table class="w-full">
                    <thead>
                        <tr class="border-b bg-[#fafafa]">
                            <th class="p-4 text-start">
                                {{ __("store.Provider") }}
                            </th>
                            <th class="p-4 text-start">
                                {{ __("store.Tracking Number") }}
                            </th>
                            <th class="p-4 text-start">
                                {{ __("store.Date") }}
                            </th>
                            <th class="p-4 text-start"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border p-4 uppercase">
                                {{ $shippingOrder->carrier }}
                            </td>
                            <td class="border p-4">
                                {{ $shippingOrder->tracking_number }}
                            </td>
                            <td class="border p-4">
                                {{ formattedDate($shippingOrder->created_at) }}
                            </td>
                            <td class="border p-4">
                                <a
                                    href="{{ $shippingOrder->tracking_url }}"
                                    rel="noindex, nofollow"
                                    class="rounded-md bg-black px-6 py-2 text-sm font-medium text-white hover:bg-gray-900"
                                >
                                    {{ __("store.Track") }}
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Layout -->
            <div class="block space-y-4 md:hidden">
                <div class="rounded-lg border bg-white p-4">
                    <div class="space-y-2">
                        <!-- Provider -->
                        <div>
                            <span class="text-sm text-gray-500">
                                {{ __("store.Provider") }}
                            </span>
                            <p class="font-medium uppercase">
                                {{ $shippingOrder->carrier }}
                            </p>
                        </div>

                        <!-- Tracking Number -->
                        <div>
                            <span class="text-sm text-gray-500">
                                {{ __("store.Tracking Number") }}
                            </span>
                            <p class="font-medium">
                                {{ $shippingOrder->tracking_number }}
                            </p>
                        </div>

                        <!-- Date -->
                        <div>
                            <span class="text-sm text-gray-500">
                                {{ __("store.Date") }}
                            </span>
                            <p class="font-medium">
                                {{ formattedDate($shippingOrder->created_at) }}
                            </p>
                        </div>

                        <!-- Track Button -->
                        <div class="pt-2">
                            <a
                                href="https://expressapi.dhl.com/mydhlapi/shipments/{{ $shippingOrder->tracking_number }}/tracking"
                                rel="noindex, nofollow"
                                class="block w-full rounded-md bg-black py-2 text-center text-sm font-medium text-white hover:bg-gray-900"
                            >
                                {{ __("store.Track") }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
