@props([
    "title",
    "collection",
    "route",
    "param" => "slug",
])

<div class="mt-6">
    <h2 class="text-[11px] uppercase text-[#8c92a4]">
        {{ $title }}
    </h2>
    <div class="my-2 h-[1px] bg-gray-200"></div>
    <ul class="mt-2 flex flex-col gap-y-2 font-semibold">
        @foreach ($collection as $result)
            <li>
                <a href="{{ route($route, [$param => $result]) }}">
                    {{ $result->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
