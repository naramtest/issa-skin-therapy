<x-home.section-container
    class="content-x-padding relative z-10 -translate-y-10 bg-lightColor pt-20"
>
    <div class="flex items-center justify-between">
        <h2 class="headline-font px-9">{{ __("store.Our Blogs") }}</h2>
        <a
            class="flex items-center rounded-3xl border border-gray-300 px-5 py-3"
            href="{{ route("posts.index") }}"
        >
            <img src="{{ asset("storage/icons/blog-date.svg") }}" alt="" />
            <span class="ms-2 text-sm">{{ __("store.View All") }}</span>
        </a>
    </div>
    <div class="mt-10">
        <div class="flex flex-col gap-10 lg:grid lg:grid-cols-6">
            <x-posts.first-list-item :post="$posts[0]" />
            <div class="col-span-3 flex h-[42rem] flex-col justify-between">
                <x-posts.second-list-item :post="$posts[1]" />
                <div class="h-[1px] w-full bg-gray-200"></div>
                <x-posts.second-list-item :post="$posts[2]" />
            </div>
        </div>
    </div>
</x-home.section-container>
