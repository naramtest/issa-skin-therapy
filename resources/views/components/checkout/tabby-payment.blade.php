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
                    if (this.tabbyInstance) {
                        this.tabbyInstance.destroy()
                    }
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
        x-on:totals-updated="initTabby($event.detail.total)"
        class="flex w-full items-center justify-between"
    >
        <div id="tabbyCard"></div>
    </div>
</div>

@push("scripts")
    @if ($isAvailable)
        <script src="https://checkout.tabby.ai/tabby-card.js"></script>
    @endif
@endpush
