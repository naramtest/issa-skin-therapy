<div
    x-data="{
        languageOptions: [
            { value: 'en', label: 'English' },
            { value: 'ar', label: 'Arabic' },
        ],
    }"
    x-show="mobileMenu"
    x-cloak
    class="menu-panel fixed inset-x-0 bottom-0 z-50 h-[91vh] transform rounded-t-[20px] bg-white shadow-lg md:h-[80vh]"
    style="transform: translateY(100%)"
    :style="mobileMenu ? 'transform: translateY(0)' : 'transform: translateY(100%)'"
>
    <div class="relative z-[200] h-full overflow-y-auto px-6 pt-6">
        <!-- Drag Handle -->
        <div
            class="absolute left-1/2 top-2 h-1 w-12 -translate-x-1/2 rounded-full bg-gray-300"
        ></div>

        <div class="mt-4">
            <ul class="menu-items space-y-6">
                @foreach (\App\Services\Nav::headerPages() as $page)
                    <li class="translate-y-8 transform opacity-0">
                        <a
                            href="{{ route($page["route"]) }}"
                            class="block text-xl font-medium text-gray-900 transition-colors hover:text-gray-600"
                            @click="mobileMenu = false"
                        >
                            {{ $page["title"] }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <!-- Additional Mobile Menu Content -->
            <div class="fixed bottom-0 left-0 right-0 border-t">
                <!-- Language and Currency Selectors -->
                <div
                    class="flex w-full items-center justify-between gap-4 px-4 py-3"
                >
                    <!-- Language Selector -->
                    <div class="relative flex-1">
                        <x-layout.header.mobile-select
                            :options="[
                                ['value' => 'en', 'label' => 'English'],
                                ['value' => 'ar', 'label' => 'Arabic']
                            ]"
                            placeholder="Select Language"
                            name="language"
                        />
                    </div>

                    <div class="h-[20px] w-[1px] bg-gray-200"></div>

                    <!-- Currency Selector -->
                    <div class="relative flex-1">
                        <livewire:currency-selector
                            :is-mobile="true"
                            location="bottom"
                        />
                    </div>
                </div>

                <!-- Login Button -->
                <div class="flex justify-between bg-[#1717170b] px-6 py-3">
                    <a
                        href="{{ Auth::check() ? route("account.index") : route("login") }}"
                        class="flex items-center justify-center gap-x-2 rounded-full bg-black py-2 pe-6 ps-5 text-sm font-medium text-white transition hover:bg-gray-900"
                    >
                        <x-gmdi-person class="h-5 w-5" />
                        <span>{{ __("store.Login") }}</span>
                    </a>

                    <!-- Social Links -->
                    <x-layout.header.home.social
                        class="gap-x-4"
                        color="text-black"
                        hover="text-gray-600"
                    />
                </div>
            </div>
        </div>
    </div>
</div>
