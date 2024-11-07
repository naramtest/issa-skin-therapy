<div
    {{ $attributes->class(["overflow-hidden"]) }}
    x-data="marquee({ speed: {{ $speed }}, gap: {{ $gap }} })"
>
    <div
        x-ref="marqueeContent"
        class="flex items-center whitespace-nowrap"
        style="gap: {{ $gap }}px"
    >
        @for ($i = 0; $i < $repeat; $i++)
            {{ $slot }}
        @endfor
    </div>
</div>
