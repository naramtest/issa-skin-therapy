@props([
    /**@var\Illuminate\Database\Eloquent\Collection*/"categories",
    "selectedCategory",
])

<div x-data="categoriesSwiper()">
    <div class="categories-swiper swiper">
        <ul class="swiper-wrapper flex flex-1 items-center gap-x-4">
            @foreach ($categories as $category)
                @unless ($category->slug === "hydrate-protect" and $category->id !== 2)
                    <x-product.categories-item
                        id="{{$category->id}}"
                        name="{{$category->name}}"
                        :is-active="$selectedCategory === $category->id"
                    />
                @endunless
            @endforeach

            <x-product.categories-item
                id="-1"
                name="The Collections"
                :is-active="$selectedCategory === -1"
            />
        </ul>
    </div>
</div>

@pushonce("scripts")
    <script>
        function categoriesSwiper() {
            return {
                categoriesSwiper: new Swiper('.categories-swiper', {
                    slidesPerView: 'auto',
                    spaceBetween: 10,
                    loop: false,
                    init() {
                        this.collectionSwiper.init();
                    },
                }),
            };
        }
    </script>
@endpushonce
