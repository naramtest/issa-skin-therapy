{{-- Payment Methods --}}

@props([
    "total",
    "error",
    "selectedMethod",
    "rejectionReason",
    "isAvailable",
])
<div>
    <div class="">
        <div class="px-4">
            <div class="mt-6 space-y-6">
                {{-- Credit Card Payment Method --}}
                <div class="relative flex gap-x-4">
                    <div class="flex h-6 items-center">
                        <input
                            id="card"
                            name="payment_method"
                            type="radio"
                            wire:model.live="form.payment_method"
                            value="{{ \App\Enums\Checkout\PaymentMethod::CARD->value }}"
                            class="text-primary focus:ring-primary h-4 w-4 border-gray-300"
                        />
                    </div>
                    <div class="flex items-center">
                        <label
                            for="card"
                            class="flex items-center text-sm font-medium leading-6 text-gray-900"
                        >
                            <x-heroicon-o-credit-card class="me-2 h-5 w-5" />
                            {{ __("store.Credit Card") }}
                        </label>
                    </div>
                </div>

                {{--  --}}
                {{-- Tabby Payment Method --}}
                @if (App::isLocal())
                    <div>
                        @if ($isAvailable)
                            <div class="relative flex gap-x-4 pb-4">
                                <div class="flex h-6 items-center">
                                    <input
                                        id="tabby"
                                        name="payment_method"
                                        type="radio"
                                        wire:model.live="form.payment_method"
                                        value="{{ \App\Enums\Checkout\PaymentMethod::TABBY->value }}"
                                        class="text-primary focus:ring-primary h-4 w-4 border-gray-300"
                                    />
                                </div>
                                <label
                                    for="tabby"
                                    class="flex items-center gap-x-2 text-sm font-medium leading-6 text-gray-900"
                                >
                                    <img
                                        class="h-5 w-auto"
                                        src="{{ asset("storage/icons/tabby.svg") }}"
                                        alt=""
                                    />
                                    <p>
                                        {{ __("store.Pay in 4. No interest, no fees") }}
                                    </p>
                                </label>
                            </div>

                            <div
                                x-cloak
                                x-data="{ show: false }"
                                x-effect="show = $wire.form.payment_method === 'tabby'"
                            >
                                <div
                                    x-show="show"
                                    x-collapse.duration.800ms
                                    class="mt-4 border-t border-gray-200 p-4"
                                >
                                    <x-checkout.tabby-payment
                                        :total="$total"
                                        :is-available="$isAvailable"
                                    />
                                </div>
                            </div>
                        @else
                            @if ($rejectionReason)
                                <div class="mt-4 rounded-lg bg-red-50 p-4">
                                    <div class="text-sm text-red-700">
                                        @if ($rejectionReason === "order_amount_too_high")
                                            {{ __("store.This purchase is above your current spending limit with Tabby, try a smaller cart or use another payment method") }}
                                        @elseif ($rejectionReason === "order_amount_too_low")
                                            {{ __("store.The purchase amount is below the minimum amount required to use Tabby, try adding more items or use another payment method") }}
                                        @else
                                            {{ __("store.Sorry, Tabby is unable to approve this purchase. Please use an alternative payment method for your order") }}
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Payment Method Details Section --}}
        <div
            x-cloak
            x-data="{ show: true }"
            x-effect="show = $wire.form.payment_method === 'card'"
        >
            <div
                x-show="show"
                x-collapse.duration.800ms
                class="mt-4 border-t border-gray-200 p-4"
            >
                <x-checkout.stripe-payment-element :total="$total" />
            </div>
        </div>
    </div>
</div>
