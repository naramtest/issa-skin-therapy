<div {{ $attributes->class([""]) }}>
    <div
        @click=" pervSlide()"
        class="cursor-pointer rounded-full border border-darkColor px-4 py-3 transition-colors duration-200 hover:border-transparent hover:bg-gray-100"
    >
        <img
            src="{{ asset("storage/icons/small-arrow-left.svg") }}"
            alt="{{ __("store.Arrow Left") }}"
        />
    </div>
    <div
        @click="nextSlide()"
        class="ms-3 cursor-pointer rounded-full border border-darkColor px-4 py-3 transition-colors duration-200 hover:border-transparent hover:bg-gray-100"
    >
        <img
            src="{{ asset("storage/icons/small-arrow-right.svg") }}"
            alt="{{ __("store.Arrow Right") }}"
        />
    </div>
</div>
