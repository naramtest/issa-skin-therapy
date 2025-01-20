<div class="space-y-4">
    {{-- Subtotal --}}
    <div class="flex justify-between">
        <span class="text-gray-600 dark:text-gray-200">
            {{ __("store.Subtotal") }}
        </span>
        <span class="font-medium">
            <x-price :money="$getRecord()->getMoneySubtotal()" />
        </span>
    </div>

    {{-- Shipping Cost --}}
    @if ($getRecord()->shipping_cost > 0)
        <div class="flex justify-between">
            <span class="text-gray-600">{{ __("store.Shipping") }}</span>
            <span class="font-medium">
                <x-price :money="$getRecord()->getMoneyShippingCost()" />
            </span>
        </div>
    @endif

    {{-- Coupon Discount --}}
    @if ($getRecord()->couponUsage)
        <div class="flex justify-between text-green-600">
            <span>
                {{ __("store.Discount") }}
                ({{ $getRecord()->couponUsage->coupon->code }})
            </span>
            <span class="font-medium">
                -
                <x-price
                    :money="new \Money\Money($getRecord()->couponUsage->discount_amount, \App\Services\Currency\CurrencyHelper::defaultCurrency())"
                />
            </span>
        </div>
    @endif

    {{-- Total --}}
    <div class="flex justify-between border-t pt-4">
        <span class="text-lg font-semibold">{{ __("store.Total") }}</span>
        <span class="text-lg font-semibold">
            <x-price :money="$getRecord()->getMoneyTotal()" />
        </span>
    </div>

    {{-- Payment Info --}}
    @if ($getRecord()->payment_method_details)
        <div class="mt-6 border-t pt-4 dark:text-gray-200">
            <h4 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">
                Details
            </h4>
            <div class="text-sm text-gray-600 dark:text-gray-200">
                <div class="flex justify-between">
                    <span>{{ __("store.Payment Method") }}</span>
                    <span>
                        {{ $getRecord()->payment_method_details["type"] ?? "N/A" }}
                    </span>
                </div>
                @if (isset($getRecord()->payment_method_details["last4"]))
                    <div class="mt-1 flex justify-between dark:text-gray-200">
                        <span>{{ __("dashboard.Title") }}</span>
                        <div>
                            <span class="uppercase">
                                {{ $getRecord()->payment_method_details["brand"] }}
                            </span>
                            <span class="ms-1">
                                {{ __("store.Ending in") }}
                            </span>
                            <span class="ms-1">
                                {{ $getRecord()->payment_method_details["last4"] }}
                            </span>
                        </div>
                    </div>
                @endif

                @if (isset($getRecord()->payment_method_details["exp_month"]) and isset($getRecord()->payment_method_details["exp_year"]))
                    <div class="mt-1 flex justify-between">
                        <span>Exp</span>
                        <span>
                            {{ $getRecord()->payment_method_details["exp_month"] }}
                            /
                            {{ $getRecord()->payment_method_details["exp_year"] }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
