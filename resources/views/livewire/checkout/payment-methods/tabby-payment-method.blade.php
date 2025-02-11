{{-- resources/views/livewire/checkout/payment-methods/tabby-payment-method.blade.php --}}
<div class="ms-10 mt-2">
    <div class="flex w-full items-center justify-between">
        <div id="tabbyCard"></div>
    </div>
</div>

@push("scripts")
    @if ($isAvailable)
        <script src="https://checkout.tabby.ai/tabby-card.js"></script>
        <script>
            new TabbyCard({
                selector: '#tabbyCard',
                currency:
                    '{{ app(\App\Services\Currency\CurrencyHelper::class)->getUserCurrency() }}',
                lang: '{{ app()->getLocale() }}',
                price: {{ $price }},
                size: 'wide',
                theme: 'default',
                header: true,
            });
        </script>
    @endif
@endpush
