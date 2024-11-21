@props([
    "money",
])

<bdi {{ $attributes }}>
    {{ \App\Services\Currency\Currency::convertToUserCurrencyWithCache($money) }}
</bdi>
