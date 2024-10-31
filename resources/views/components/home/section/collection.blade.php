<section
    {{ $attributes->class(["content-x-padding mt-10 rounded-t-[20px] border-t-[1px] border-[#A6BCC599] py-14"]) }}
>
    <div class="flex items-center justify-between px-3">
        <div class="w-[25%]">
            <x-home.fancy-heading />
            <a
                class="mt-10 flex w-fit items-center rounded-[50px] border border-[#a5bbc4] px-6 py-4 text-[15px] font-medium transition-colors duration-300 hover:border-transparent hover:bg-lightAccentColor"
                href=""
            >
                <span>Our Story</span>
                <x-icons.arrow-right class="ms-3 h-4 w-4 text-black" />
            </a>
        </div>
        <ol class="w-[60%] text-2xl">
            <li class="mb-1">
                {{ __("store.1. Crafted by Dr") }}
            </li>
            <li class="mb-1">
                {{ __("store.Revolutionary Patent Delivery Technology") }}
            </li>
            <li>{{ __("store.Premium Quality, Made in USA.") }}</li>
        </ol>
    </div>
    <x-home.collection-row />
    <x-home.collection-row />
</section>
