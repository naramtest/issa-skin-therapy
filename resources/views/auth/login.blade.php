<x-store-main-layout>
    <main class="flex h-full w-full flex-col items-center justify-center py-20">
        <h1 class="text-[95px] font-bold">{{ __("store.Login") }}</h1>
        <div class="mt-6 w-[30%] rounded-2xl p-8 shadow-2xl">
            <form action="">
                <x-auth.components.input
                    label="Email Address"
                    placeholder="Enter your email here ..."
                    type="email"
                    field="email"
                />
                <x-auth.components.input
                    label="{{ __('dashboard.Password') }}"
                    placeholder="{{ __('dashboard.Password') }}"
                    field="password"
                />
                <div
                    class="mt-6 flex w-full items-center justify-between"
                ></div>
            </form>
        </div>
    </main>
</x-store-main-layout>
