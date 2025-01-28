<div
    {{
        $attributes->class([
            "overflow-hidden",
            "rtl:scale-x-[-1]", // Add RTL transform
        ])
    }}
    x-data="marquee({
                speed: {{ $speed }},
                gap: {{ $gap }},
                direction: '{{ $direction }}',
                isRtl: '{{ app()->getLocale() === "ar" }}',
            })"
>
    <div
        x-ref="marqueeContent"
        class="flex items-center whitespace-nowrap rtl:scale-x-[-1]"
        {{-- Add RTL transform to content --}}
        style="gap: {{ $gap }}px"
    >
        @for ($i = 0; $i < $repeat; $i++)
            {{ $slot }}
        @endfor
    </div>
</div>
