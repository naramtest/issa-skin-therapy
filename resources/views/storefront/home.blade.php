<x-store-main-layout>
    <main class="relative">
        <x-home.section.hero-swiper />
        <x-home.section.collection />
        <section
            class="content-x-padding relative flex w-full flex-col items-start gap-5 lg:flex-row"
        >
            <div class="w-full lg:w-[55%]">
                <div
                    x-data="{
                        currentImageIndex: 0,
                        images: [
                            {
                                id: 1,
                                thumb: '{{ asset("storage/test/1.webp") }}',
                                full: '{{ asset("storage/test/1.webp") }}',
                                alt: 'Product view 1',
                            },
                            {
                                id: 2,
                                thumb: '{{ asset("storage/test/2.webp") }}',
                                full: '{{ asset("storage/test/2.webp") }}',
                                alt: 'Product view 2',
                            },
                            {
                                id: 3,
                                thumb: '{{ asset("storage/test/3.webp") }}',
                                full: '{{ asset("storage/test/3.webp") }}',
                                alt: 'Product view 3',
                            },
                            {
                                id: 4,
                                thumb: '{{ asset("storage/test/4.webp") }}',
                                full: '{{ asset("storage/test/4.webp") }}',
                                alt: 'Product view 4',
                            },
                        ],
                    }"
                    class="mx-auto flex max-w-4xl gap-4 p-4"
                >
                    <!-- Thumbnails Column -->
                    <div class="flex w-24 flex-col gap-3">
                        <template
                            x-for="(image, index) in images"
                            :key="image.id"
                        >
                            <button
                                @click="currentImageIndex = index"
                                :class="currentImageIndex === index ? 'border-blue-500 shadow-lg' : 'border-transparent hover:border-gray-300'"
                                class="overflow-hidden rounded-lg border-2 transition-all"
                            >
                                <img
                                    :src="image.thumb"
                                    :alt="image.alt"
                                    class="h-24 w-full object-cover"
                                />
                            </button>
                        </template>
                    </div>

                    <!-- Main Image -->
                    <div class="relative flex-1">
                        <div
                            class="aspect-square overflow-hidden rounded-xl bg-gray-100"
                        >
                            <img
                                :src="images[currentImageIndex].full"
                                :alt="images[currentImageIndex].alt"
                                class="h-full w-full object-cover"
                            />
                        </div>

                        <!-- Navigation Buttons -->
                        <div
                            class="absolute inset-y-0 left-0 right-0 flex items-center justify-between"
                        >
                            <button
                                @click="currentImageIndex = Math.max(0, currentImageIndex - 1)"
                                class="ml-4 rounded-full bg-white/80 p-2 shadow-lg transition-colors hover:bg-white disabled:opacity-50"
                                :disabled="currentImageIndex === 0"
                            >
                                <svg
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 19l-7-7 7-7"
                                    />
                                </svg>
                            </button>

                            <button
                                @click="currentImageIndex = Math.min(images.length - 1, currentImageIndex + 1)"
                                class="mr-4 rounded-full bg-white/80 p-2 shadow-lg transition-colors hover:bg-white disabled:opacity-50"
                                :disabled="currentImageIndex === images.length - 1"
                            >
                                <svg
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full lg:w-[45%]"></div>
        </section>
        <x-home.section.vedio-background />
        <x-home.section.boxes />
        <div class="h-[1000px] w-full"></div>
    </main>
</x-store-main-layout>
