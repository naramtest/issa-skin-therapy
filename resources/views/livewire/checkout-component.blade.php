<div class="padding-from-side-menu bg-lightColor py-12">
    <form wire:submit="placeOrderAndPay" class="mx-auto">
        <div class="grid grid-cols-1 gap-x-6 lg:grid-cols-[56%_auto]">
            <!-- Left Column - Information Form -->

            <x-checkout.sections.form
                :form="$form"
                :billing-cities="$billingCities"
                :billing-state="$billingStates"
                :shipping-cities="$shippingCities"
                :shipping-states="$shippingStates"
                :countries="$countries"
            />

            <!-- Right Column - Order Summary -->
            <div class="relative rounded-lg">
                <div class="sticky top-[90px]">
                    <x-checkout.sections.summary
                        :cart-items="$this->cartItems"
                        :subtotal="$this->subtotal"
                        :total="$this->total"
                        :selected-shipping-rate="$selectedShippingRate"
                        :shipping-rates="$shippingRates"
                        :discount="$this->discount"
                    />
                    <!-- Coupon Code -->
                    <x-checkout.sections.coupon
                        :coupon-error="$couponError"
                        :form="$form"
                    />

                    <div class="mt-8 rounded-[15px] border p-8">
                        <h3 class="mb-4 text-lg font-medium">
                            {{ __("store.Payment Details") }}
                        </h3>

                        <!-- Payment -->
                        <x-checkout.sections.payment-methods-component
                            :error="$error"
                            :total="$this->total->getAmount()"
                            :selected-method="$form->payment_method"
                            :rejection-reason="$rejectionReason"
                            :is-available="$isAvailable"
                        />
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
                                wire:loading.attr="disabled"
                                @disabled($processing)
                                class="w-full rounded-full bg-black px-6 py-3 text-white hover:bg-gray-800 disabled:opacity-50"
                            >
                                @if (! $processing)
                                    <span wire:loading.remove>
                                        {{ __("store.Place Order and Pay") }}
                                    </span>
                                    <span wire:loading>
                                        {{ __("store.Processing") }} ...
                                    </span>
                                @else
                                    <span>
                                        {{ __("store.Processing") }} ...
                                    </span>
                                @endif
                            </button>

                            @if ($error)
                                <div
                                    class="mt-4 rounded-lg bg-red-50 p-4 text-red-600"
                                >
                                    {{ $error }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            x-on:payment-ready.window="
                const stripe = window.stripe
                const elements = window.stripeElements

                if (! stripe || ! elements) {
                    $wire.dispatch('payment-error', {
                        error: 'Payment system not initialized properly',
                    })
                    return
                }

                try {
                    const { error: submitError } = await elements.submit()
                    if (submitError) {
                        $wire.dispatch('payment-error', {
                            error: submitError.message,
                        })
                        return
                    }

                    // Get billing details from the form
                    const billingDetails = await $wire.getBillingDetails()

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

                        $wire.dispatch('payment-error', {
                            error: errorMessage,
                        })
                    }
                } catch (e) {
                    $wire.dispatch('payment-error', {
                        error: 'An unexpected error occurred while processing your payment. Please try again.',
                    })
                }
            "
        ></div>
    </form>
    @php
        $pixelContent = $this->getPixelArray();
    @endphp

    @push("scripts")
        <script>
            window.dataLayer = window.dataLayer || [];

            window.dataLayer.push({
                event: 'InitiateCheckout',
                contents: @json($pixelContent["facebook"]),
                contents_tiktok: @json($pixelContent["tikTok"]),
                currency:
                    '{{ \App\Services\Currency\CurrencyHelper::getCurrencyCode() }}',
                num_items: {{ count($this->cartItems) }},
                value: {{ \App\Services\Currency\CurrencyHelper::decimalFormatter($this->total) }},
            });
        </script>
    @endpush
</div>
