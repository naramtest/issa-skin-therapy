@props([
    "total",
    "isAvailable",
])
@php
    $money = new \Money\Money($total, new \Money\Currency(\App\Services\Currency\CurrencyHelper::getUserCurrency()));
    $moneyTotal = \App\Services\Currency\CurrencyHelper::decimalFormatter($money);
@endphp

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
                currency: '{{ $money->getCurrency() }}',
                lang: '{{ app()->getLocale() }}',
                price: {{ $moneyTotal }},
                size: 'wide',
                theme: 'default',
                header: true,
            });
        </script>
    @endif
@endpush
