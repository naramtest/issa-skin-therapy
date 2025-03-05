@props([
    "total",
    "isAvailable",
])
@php
    $money = new \Money\Money($total, new \Money\Currency(\App\Services\Currency\CurrencyHelper::getUserCurrency()));
    $moneyTotal = \App\Services\Currency\CurrencyHelper::decimalFormatter($money);
@endphp

<div class="ms-10 mt-2">
    <div
        x-data="{
            initTabby(total) {
                if (typeof TabbyCard !== 'undefined') {
                    // Destroy existing instance if it exists
                    // Create new instance
                    this.tabbyInstance = new TabbyCard({
                        selector: '#tabbyCard',
                        currency: '{{ $money->getCurrency() }}',
                        lang: '{{ app()->getLocale() }}',
                        price: {{ $moneyTotal }},
                        size: 'wide',
                        theme: 'default',
                        header: true,
                    })
                }
            },
            tabbyInstance: null,
        }"
        x-init="initTabby({{ $total }})"
        class="flex w-full items-center justify-between"
    >
        <div wire:ignore id="tabbyCard"></div>
    </div>
</div>

@push("scripts")
    <script src="https://checkout.tabby.ai/tabby-card.js"></script>
@endpush
