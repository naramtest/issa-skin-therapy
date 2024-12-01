@props([
    "color" => "text-white",
    "height" => "h-[1.4rem]",
    "width" => "w-[1.4rem]",
    "hover" => "text-gray-300",
])

<ul
    {{ $attributes->class(["flex  items-center justify-start "]) }}
>
    @foreach ($info->social as $social)
        <x-share.icon
            class="{{$height}} {{$width}} {{$color}} hover:{{$hover}}"
            name="{{$social['name']}}"
            url="{{$social['url']}}"
        />
    @endforeach
</ul>
