@props([
    "title",
    "items",
])
<div>
    <h3 class="text-xl font-semibold">Profile</h3>
    <ul class="ms-1 mt-4 font-light leading-[20px]">
        @foreach ($items as $item)
            <li class="hover:text-secondaryColor mt-3">
                <a href="{{ route($item["route"]) }}">
                    <span>{{ $item["name"] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
