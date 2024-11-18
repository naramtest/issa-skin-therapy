@props([
    "money",
])

<bdi {{ $attributes }}>
    {{ \App\Services\Store\Currency\Currency::convertToUserCurrencyWithCache($money) }}
</bdi>
