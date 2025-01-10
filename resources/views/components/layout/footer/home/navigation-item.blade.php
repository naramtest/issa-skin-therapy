@props([
    "title",
    "items",
])
<div class="text-center lg:text-start">
    <h3 class="text-xl font-semibold">{{ $title }}</h3>
    <ul class="mt-4 font-light leading-[20px] lg:ms-1">
        @foreach ($items as $item)
            <li class="mt-3 hover:text-secondaryColor">
                <a href="{{ route($item["route"]) }}">
                    <span>{{ $item["name"] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
