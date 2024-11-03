<x-home.section-container class="py-14">
    <div class="flex flex-col items-center">
        <p class="text-center text-lg">Skin That Defies Times</p>
        <h2 class="headline-font gradient-text mt-2 text-[#333F43]">
            See The Difference
        </h2>
    </div>
    <x-general.image-comparison
        before-image="{{ asset('storage/test/home-before.jpeg') }}"
        after-image="{{ asset('storage/test/home-after.jpeg') }}"
        before-alt="Product before modification"
        after-alt="Product after modification"
        class="mt-9 h-[750px]"
        overlay-text="naram alkoht"
    />
</x-home.section-container>
