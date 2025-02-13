@props([
    "money",
    "currency",
])
<bdi {{ $attributes }}>
    {{ \App\Services\Currency\Currency::convertWithCache($money, \App\Services\Currency\CurrencyHelper::defaultCurrency(), $currency) }}
</bdi>
