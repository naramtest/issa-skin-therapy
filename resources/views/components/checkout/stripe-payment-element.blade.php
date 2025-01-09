@php
    $stripeKey = config("services.stripe.api_key");
    $userCurrency = \App\Services\Currency\CurrencyHelper::getUserCurrency();
@endphp

<div
    wire:ignore
    x-data="stripePayment()"
    x-init="mount('{{ $stripeKey }}', '{{ strtolower($userCurrency) }}')"
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

                mount(key, currency) {
                    if (this.stripe) return;

                    this.stripe = Stripe(key);
                    window.stripe = this.stripe;
                    this.initializeElements(currency);
                },

                async initializeElements(currency) {
                    // Create Elements instance without specifying payment methods
                    this.elements = this.stripe.elements({
                        mode: 'payment',
                        amount: 1000, // Will be set when creating PaymentIntent
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

                    // Create and mount the Payment Element
                    this.paymentElement = this.elements.create('payment', {
                        defaultValues: {
                            billingDetails: {
                                name: '',
                                email: '',
                                phone: '',
                                address: {
                                    country: '',
                                },
                            },
                        },
                        fields: {
                            billingDetails: 'never', // We'll collect this in our checkout form
                        },
                    });

                    this.paymentElement.mount('#payment-element');
                    window.stripeElements = this.elements;

                    // Handle changes/errors
                    this.paymentElement.on('change', (event) => {
                        if (event.error) {
                            this.errorMessage = event.error.message;
                        } else {
                            this.errorMessage = '';
                        }
                    });
                },

                async updatePaymentElement(clientSecret) {
                    if (this.elements) {
                        await this.elements.update({
                            clientSecret: clientSecret,
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
