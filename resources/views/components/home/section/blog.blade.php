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
            <x-posts.first-list-item
                class="col-span-6 lg:col-span-3"
                :post="$posts[0]"
            />
            <div
                class="col-span-6 flex h-[42rem] flex-col justify-between lg:col-span-3"
            >
                <x-posts.second-list-item :post="$posts[1]" />
                <div class="my-2 h-[1px] w-full bg-gray-200"></div>
                <x-posts.second-list-item :post="$posts[2]" />
            </div>
        </div>
    </div>
</x-home.section-container>
