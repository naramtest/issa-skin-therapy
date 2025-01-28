@props([
    "faqs",
])

<x-home.section-container class="content-x-padding pt-12">
    <div
        class="flex h-fit w-full flex-col overflow-hidden rounded-t-3xl lg:flex-row"
    >
        <div class="h-auto bg-darkColor px-4 py-12 lg:w-[70%] lg:px-8">
            <div class="flex flex-row items-center justify-between gap-x-2">
                <h2
                    class="text-2xl font-[800] text-lightColor md:text-6xl rtl:md:text-4xl"
                >
                    {{ __("store.FAQs") }}
                </h2>
                <div
                    class="w-fit rounded-xl bg-[#2D2D2D] px-3 py-2 text-xs font-light text-[#8C92A4] lg:px-6 lg:py-4"
                >
                    <p>
                        {{ __("store.Please read our") }}
                        <a
                            class="text-lightColor underline"
                            href="{{ route("faq.index") }}"
                        >
                            {{ __("store.FAQs") }}
                        </a>
                        {{ __("store.page to find out more") }}
                    </p>
                </div>
            </div>
            <div
                x-data="{
                    activeIndex: -1,
                    selectActive(index) {
                        this.activeIndex = this.activeIndex != index ? index : -1
                    },
                    isActive(index) {
                        return this.activeIndex == index
                    },
                }"
                class="mt-10 w-full overflow-hidden px-3 text-lightColor"
            >
                @foreach ($faqs as $faq)
                    <x-product.faqs-item :index="$loop->index" :faq="$faq" />
                @endforeach
            </div>
        </div>
        <div class="h-auto bg-[#2D2D2D] px-8 py-12 text-lightColor lg:w-[30%]">
            <div class="flex items-center justify-between gap-x-4">
                <h2 class="text-2xl font-[600]">
                    {{ __("store.Didnt find you answer?") }}
                </h2>
                <img
                    class="w-[30%]"
                    src="{{ asset("storage/images/clients.webp") }}"
                    alt="{{ __("store.Clients") }}"
                />
            </div>
            <p class="my-7">{{ __("store.Don’t hesitate to contact us") }}</p>
            <form class="flex flex-col gap-y-4">
                <x-product.contact-input
                    placeholder="{{ __('store.Name') }}"
                    field="name"
                />
                <x-product.contact-input
                    placeholder="{{__('store.Email')}}"
                    field="email"
                    type="email"
                />
                <x-product.contact-textarea
                    placeholder="Message"
                    field="message"
                />

                <button type="submit" class="mt-2 w-fit">
                    <x-general.button-black-animation class="!py-3 px-6">
                        <span class="relative z-10 !normal-case">
                            {{ __("store.Send Message") }}
                        </span>
                    </x-general.button-black-animation>
                </button>
            </form>
        </div>
    </div>
</x-home.section-container>
