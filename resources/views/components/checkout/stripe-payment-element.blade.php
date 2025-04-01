@props([
    "total",
])

@php
    $stripeKey = config("services.stripe.api_key");
    $userCurrency = \App\Services\Currency\CurrencyHelper::getUserCurrency();
@endphp

<div
    wire:ignore
    x-data="stripePayment()"
    x-init="mount('{{ $stripeKey }}', '{{ strtolower($userCurrency) }}', {{ $total }})"
    x-effect="updateAmount({{ $total }})"
    class="w-full"
>
    <div class="space-y-4">
        <div id="payment-element" class="w-full"></div>

        <div
            x-show="errorMessage"
            x-text="errorMessage"
            class="mt-2 text-sm text-red-600"
        ></div>
    </div>
</div>

@push("scripts")
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('stripePayment', () => ({
                stripe: null,
                elements: null,
                paymentElement: null,
                errorMessage: '',

                mount(key, currency, amount) {
                    if (this.stripe) return;
                    this.stripe = Stripe(key);
                    window.stripe = this.stripe;
                    this.initializeElements(currency, amount);
                },

                async initializeElements(currency, amount) {
                    this.elements = this.stripe.elements({
                        mode: 'payment',
                        amount: amount,
                        currency: currency,
                        appearance: {
                            theme: 'stripe',
                            variables: {
                                colorPrimary: '#0A2540',
                                colorBackground: '#ffffff',
                                colorText: '#30313d',
                                colorDanger: '#df1b41',
                                fontFamily:
                                    'system-ui, -apple-system, "Segoe UI", Roboto, sans-serif',
                                spacingUnit: '4px',
                                borderRadius: '8px',
                            },
                        },
                    });

                    // Remove the defaultValues and fields configuration
                    this.paymentElement = this.elements.create('payment');
                    this.paymentElement.mount('#payment-element');
                    window.stripeElements = this.elements;

                    this.paymentElement.on('change', (event) => {
                        if (event.error) {
                            this.errorMessage = event.error.message;
                        } else {
                            this.errorMessage = '';
                        }
                    });
                },

                async updateAmount(amount) {
                    if (this.elements) {
                        await this.elements.update({
                            amount: amount,
                        });
                    }
                },

                destroy() {
                    if (this.paymentElement) {
                        this.paymentElement.destroy();
                    }
                },
            }));
        });
    </script>
@endpush
