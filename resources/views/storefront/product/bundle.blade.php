<x-store-main-layout>
    <main>
        <x-product.section.details :product="$bundle" :media="$media" />

        {{-- <x-bundle.video-section :bundle="$bundle" /> --}}
        <x-bundle.how-to-use-section :bundle="$bundle" :faqs="$faqs" />
        <x-bundle.extra-tips :bundle="$bundle" />

        <x-home.section-container class="padding-from-side-menu py-12">
            <h2 class="headline-font">
                {{ __("store.Included in the collection") }}
            </h2>
            <div class="w-full text-center">
                <img
                    class="mx-auto mt-8"
                    width="250px"
                    src="https://issaskintherapy.com/wp-content/uploads/2024/07/box-handle-1024x433.png"
                    alt=""
                />
                <div class="flex rounded-lg bg-[#FAFAFA] px-12 py-4">
                    @foreach ($bundle->products as $product)
                        <img
                            class="w-[200px]"
                            src="{{ $product->getFirstMedia(config("const.media.featured"))->getUrl(config("const.media.thumbnail")) }}"
                            alt=""
                        />
                    @endforeach
                </div>
            </div>
        </x-home.section-container>

        <x-product.section.faqs :faqs="$faqs" />
    </main>
</x-store-main-layout>
