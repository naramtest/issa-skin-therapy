@props([
    "color" => "text-white",
    "height" => "h-[1.4rem]",
    "width" => "w-[1.4rem]",
    "hover" => "text-gray-300",
])

<ul
    {{ $attributes->class(["flex  items-center justify-start "]) }}
>
    <x-share.icon
        class="{{$height}} {{$width}} {{$color}} hover:{{$hover}}"
        name="facebook"
        url="https://www.facebook.com/issaskintherapy"
    />
    <x-share.icon
        class="{{$height}} {{$width}} {{$color}} hover:{{$hover}}"
        name="tiktok"
        url="https://www.tiktok.com/@issa.skintherapy?_t=8oWmf2d03Ag&_r=1"
    />
    <x-share.icon
        class="{{$height}} {{$width}} {{$color}} hover:{{$hover}}"
        name="instagram"
        url="https://www.instagram.com/issaskintherapy"
    />

    <x-share.icon
        class="{{$height}} {{$width}} {{$color}} hover:{{$hover}}"
        name="youtube"
        url="https://www.youtube.com/@issaskintherapy?si=sjj6hRWeLYQb0MEC"
    />
</ul>
