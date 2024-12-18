<x-store-main-layout>
    <main class="flex h-full w-full flex-col items-center justify-center py-20">
        <h1 class="text-[95px] font-bold">{{ __("store.Login") }}</h1>
        <div
            style="box-shadow: 0 0 38px 14px rgba(2, 8, 53, 0.06)"
            class="mt-6 w-[30%] rounded-2xl p-8"
        >
            <form method="POST" action="{{ route("login.store") }}">
                @csrf
                <x-auth.components.input
                    label="{{ __('store.Email Address') }}"
                    placeholder="{{ __('store.Enter your email here ') }}"
                    type="email"
                    field="email"
                    :is-required="true"
                />
                <x-auth.components.input
                    label="{{ __('dashboard.Password') }}"
                    placeholder="{{ __('dashboard.Password') }}"
                    field="password"
                    type="password"
                    :is-required="true"
                />
                <div class="mt-6 flex w-full items-center justify-between">
                    <div>
                        <input
                            class="accent-black"
                            type="checkbox"
                            name="remember"
                            id="remember"
                        />
                        <label for="remember">
                            {{ __("dashboard.Remember Me") }}
                        </label>
                    </div>
                    <a href="{{ route("password.request") }}">
                        {{ __("dashboard.Forget Password?") }}
                    </a>
                </div>
                <x-general.button-black-animation
                    class="mt-4 !w-fit !py-2 px-6"
                >
                    <button class="relative z-10" type="submit">
                        {{ __("store.Login") }}
                    </button>
                </x-general.button-black-animation>
            </form>
        </div>
        <div class="mt-6 w-[30%]">
            <x-general.button-white-animation
                class="!w-fit !border-black !py-2 px-8 !normal-case"
            >
                <a
                    href="{{ route("register") }}"
                    class="relative z-10"
                    type="submit"
                >
                    {{ __("store.Create Account") }}
                </a>
            </x-general.button-white-animation>
        </div>

        <a href="{{ route("storefront.index") }}" class="mt-6 flex gap-x-2">
            <x-icons.back-to-store class="h-4 w-4" />
            <p>{{ __("store.Back To Store") }}</p>
        </a>
    </main>
</x-store-main-layout>
