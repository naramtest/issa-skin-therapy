<div>
    <ul
        class="my-2 flex items-center justify-center divide-x divide-[#B9B9B9] rtl:pt-6"
    >
        @foreach ($categories as $category)
            <li
                wire:click="filterByCategory({{ $category->id }})"
                class="cursor-pointer px-4 text-lg text-[#B9B9B9] hover:text-darkColor"
            >
                {{ $category->name }}
            </li>
        @endforeach
    </ul>
    <section class="content-x-padding my-14">
        <div class="grid grid-cols-6 gap-10">
            <x-posts.first-list-item
                class="col-span-6 lg:col-span-3"
                :post="$this->displayPosts[0]"
            />
            <div
                class="col-span-6 flex flex-col justify-between lg:col-span-3 lg:h-[42rem]"
            >
                <x-posts.second-list-item :post="$this->displayPosts[1]" />
                <div class="my-3 h-[1px] w-full bg-gray-200"></div>
                <x-posts.second-list-item :post="$this->displayPosts[2]" />
            </div>
            @for ($i=3 ; $i < 6 ; $i++)
                <x-posts.grid-item
                    class="col-span-6 md:col-span-3 lg:col-span-2"
                    :post="$this->displayPosts[$i]"
                />
            @endfor
        </div>
    </section>
</div>
