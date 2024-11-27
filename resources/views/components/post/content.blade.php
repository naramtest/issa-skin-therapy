@props([
    "post",
    "next",
    "past",
])

<x-home.section-container
    class="padding-from-side-menu relative z-10 -translate-y-10 bg-lightColor py-12"
>
    <div class="post-width mx-auto">
        <div class="no-tailwind post">
            {!! $post->body !!}
        </div>
        <div class="mt-4 flex items-center">
            <span class="font-medium">{{ __("store.Share") }}:</span>
            <x-general.share-icons
                color="text-darkColor"
                hover="text-gray-700"
            />
        </div>
        {{-- Posts Navigation --}}
        <div class="mt-10 flex items-center divide-x-2 divide-[#B9B9B9]">
            @if ($past)
                <a
                    href="{{ route("posts.show", $past) }}"
                    class="group flex w-1/2 cursor-pointer items-center pe-3"
                >
                    <x-gmdi-arrow-back
                        class="h-7 w-7 text-darkColor transition-transform duration-300 group-hover:-translate-x-4"
                    />
                    <p class="ms-2 line-clamp-1 text-2xl font-bold">
                        {{ $past->title }}
                    </p>
                </a>
            @else
                <div class="w-1/2"></div>
            @endif

            @if ($next)
                <a
                    href="{{ route("posts.show", $next) }}"
                    class="group flex w-1/2 cursor-pointer items-center ps-3"
                >
                    <p class="line-clamp-1 text-2xl font-bold">
                        {{ $next->title }}
                    </p>
                    <x-gmdi-arrow-forward
                        class="ms-2 h-7 w-7 text-darkColor transition-transform duration-300 group-hover:translate-x-4"
                    />
                </a>
            @else
                <div class="w-1/2"></div>
            @endif
        </div>
    </div>
</x-home.section-container>
