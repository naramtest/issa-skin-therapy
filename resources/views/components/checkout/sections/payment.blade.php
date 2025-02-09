@props([
    "error",
    "stripeAmount",
])

<div class="mt-8 rounded-[15px] border p-8">
    <h3 class="mb-4 text-lg font-medium">
        {{ __("store.Payment Details") }}
    </h3>

    <div class="mb-4">
        <x-checkout.stripe-payment-element :stripe-amount="$stripeAmount" />
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
