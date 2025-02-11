@props([
    "error",
    "stripeAmount",
    "price",
    "selectedMethod",
    "rejectionReason",
    "isAvailable",
])

<div class="mt-8 rounded-[15px] border p-8">
    <h3 class="mb-4 text-lg font-medium">
        {{ __("store.Payment Details") }}
    </h3>
    {{-- Payment Methods --}}
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
                            wire:model.live="selectedMethod"
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

                {{-- Tabby Payment Method --}}
                <div>
                    @if ($isAvailable)
                        <div class="relative flex gap-x-4 pb-4">
                            <div class="flex h-6 items-center">
                                <input
                                    id="tabby"
                                    name="payment_method"
                                    type="radio"
                                    wire:model.live="selectedMethod"
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
                            x-data="{ show: false }"
                            x-effect="show = $wire.selectedMethod === 'tabby'"
                        >
                            <div x-show="show" x-collapse.duration.800ms>
                                <livewire:checkout.payment-methods.tabby-payment-method
                                    :price="$price"
                                    :is-available="$isAvailable"
                                    :rejection-reason="$rejectionReason"
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
            </div>
        </div>

        {{-- Payment Method Details Section --}}
        <div
            x-data="{ show: false }"
            x-effect="show = $wire.selectedMethod === 'card'"
        >
            <div
                x-show="show"
                x-collapse.duration.800ms
                class="mt-4 border-t border-gray-200 p-4"
            >
                <x-checkout.stripe-payment-element />
            </div>
        </div>
    </div>
    <!-- Terms and Place Order -->
    <div class="mt-8">
        <div class="mb-4">
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    wire:model="form.terms_accepted"
                    class="rounded border-gray-300"
                />
                <span class="text-sm">
                    {{ __("store.I have read and agree to the website") }}
                    <a
                        href="{{ route("terms.index") }}"
                        class="text-blue-600 hover:underline"
                    >
                        {{ __("store.terms and conditions") }}
                    </a>
                    <span class="text-red-500">*</span>
                </span>
            </label>

            @if ($errors->has("form.terms_accepted"))
                <p class="mt-1 text-sm text-red-600">
                    {{ $errors->first("form.terms_accepted") }}
                </p>
            @endif
        </div>

        <button
            type="submit"
            x-data
            x-on:payment-ready.window="
                const stripe = window.stripe
                const elements = window.stripeElements

                if (! stripe || ! elements) {
                    $wire.set('error', 'Payment system not initialized properly')
                    return
                }

                try {
                    // First submit the elements form
                    const { error: submitError } = await elements.submit()
                    if (submitError) {
                        $wire.set('error', submitError.message)
                        return
                    }

                    const billingDetails = {
                        name:
                            $wire.get('form.billing_first_name') +
                            ' ' +
                            $wire.get('form.billing_last_name'),
                        email: $wire.get('form.email'),
                        phone: $wire.get('form.phone'),
                        address: {
                            line1: $wire.get('form.billing_address'),
                            line2:
                                $wire.get('form.billing_building') +
                                ' ' +
                                $wire.get('form.billing_flat'),
                            city: $wire.get('form.billing_city'),
                            state: $wire.get('form.billing_state'),
                            postal_code: $wire.get('form.billing_postal_code'),
                            country: $wire.get('form.billing_country'),
                        },
                    }

                    const result = await stripe.confirmPayment({
                        elements,
                        clientSecret: $event.detail.clientSecret,
                        confirmParams: {
                            return_url: '{{ route("checkout.success") }}',
                            payment_method_data: {
                                billing_details: billingDetails,
                            },
                        },
                    })

                    if (result.error) {
                        let errorMessage = result.error.message

                        switch (result.error.type) {
                            case 'card_error':
                            case 'validation_error':
                                errorMessage = result.error.message
                                break
                            case 'invalid_request_error':
                                errorMessage =
                                    'There was a problem with your payment information. Please check and try again.'
                                break
                            default:
                                errorMessage = 'An unexpected error occurred. Please try again.'
                        }

                        $wire.set('error', errorMessage)
                    }
                } catch (e) {
                    $wire.set(
                        'error',
                        'An unexpected error occurred while processing your payment. Please try again.',
                    )
                }
            "
            wire:loading.attr="disabled"
            class="w-full rounded-full bg-black px-6 py-3 text-white hover:bg-gray-800 disabled:opacity-50"
        >
            <span wire:loading.remove>
                {{ __("store.Place Order and Pay") }}
            </span>
            <span wire:loading>{{ __("store.Processing") }} ...</span>
        </button>

        @if ($error)
            <div class="mt-4 rounded-lg bg-red-50 p-4 text-red-600">
                {{ $error }}
            </div>
        @endif
    </div>
</div>
