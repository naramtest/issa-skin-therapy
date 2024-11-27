@props([
    "color" => "text-white",
    "height" => "h-[1.4rem]",
    "width" => "w-[1.4rem]",
    "hover" => "text-gray-300",
])
{{-- TODO: add share links --}}
<ul
    {{ $attributes->class(["flex ms-6  gap-x-4 items-center justify-start "]) }}
>
    <x-share.icon
        class="{{$height}} {{$width}} {{$color}} hover:{{$hover}}"
        name="facebook-r"
        url="https://www.facebook.com/issaskintherapy"
    />
    <x-share.icon
        class="{{$height}} {{$width}} {{$color}} hover:{{$hover}}"
        name="pinterest"
        url="https://www.facebook.com/issaskintherapy"
    />
    <x-share.icon
        class="{{$height}} {{$width}} {{$color}} hover:{{$hover}}"
        name="twitter"
        url="https://www.facebook.com/issaskintherapy"
    />
    <x-share.icon
        class="{{$height}} {{$width}} {{$color}} hover:{{$hover}}"
        name="email"
        url="https://www.facebook.com/issaskintherapy"
    />
</ul>
