<x-store-main-layout>
    <main class="flex h-full w-full flex-col items-center justify-center py-20">
        <h1 class="text-5xl font-bold md:text-[95px]">
            {{ __("store.Create Account") }}
        </h1>
        <div
            style="box-shadow: 0 0 38px 14px rgba(2, 8, 53, 0.06)"
            class="mt-6 w-[80%] rounded-2xl p-4 md:w-[50%] md:p-8 lg:w-[30%]"
        >
            <form method="POST" action="{{ route("register.store") }}">
                @csrf
                <x-auth.components.input
                    label="{{ __('store.First Name') }}"
                    placeholder="{{ __('store.Your First Name') }}"
                    field="first_name"
                    :is-required="true"
                />
                <x-auth.components.input
                    label="{{ __('store.Last Name') }}"
                    placeholder="{{ __('store.Your Last Name ') }}"
                    field="last_name"
                    :is-required="true"
                />
                <x-auth.components.input
                    label="{{ __('store.Email Address') }}"
                    placeholder="{{ __('store.Enter your email here ...') }}"
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
                {{-- Add this password confirmation field --}}
                <x-auth.components.input
                    label="{{ __('dashboard.Confirm Password') }}"
                    placeholder="{{ __('dashboard.Confirm Password') }}"
                    type="password"
                    field="password_confirmation"
                    :is-required="true"
                />
                <x-general.button-black-animation class="!w-fit !py-2 px-6">
                    <button class="relative z-10" type="submit">
                        {{ __("store.Create Account") }}
                    </button>
                </x-general.button-black-animation>
            </form>
        </div>
        <div class="mt-6 md:w-[30%]">
            <span>{{ __("store.If you already have an account?") }}</span>
            <a
                href="{{ route("login") }}"
                class="relative z-10 font-medium transition-transform duration-300 hover:scale-110"
                type="submit"
            >
                {{ __("store.Login") }}
            </a>
        </div>

        <a href="{{ route("storefront.index") }}" class="mt-6 flex gap-x-2">
            <x-icons.back-to-store class="h-4 w-4" />
            <p>{{ __("store.Back To Store") }}</p>
        </a>
    </main>
</x-store-main-layout>
