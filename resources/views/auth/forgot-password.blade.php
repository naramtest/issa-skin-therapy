<x-store-main-layout>
    <main class="flex h-full w-full flex-col items-center justify-center py-20">
        <h1 class="text-[95px] font-bold">{{ __("Reset Password") }}</h1>
        <div
            style="box-shadow: 0 0 38px 14px rgba(2, 8, 53, 0.06)"
            class="mt-6 w-[30%] rounded-2xl p-8"
        >
            @if (session("status"))
                <div class="mb-4 text-sm text-green-600">
                    {{ session("status") }}
                </div>
            @endif

            <form method="POST" action="{{ route("password.email") }}">
                @csrf
                <x-auth.components.input
                    label="{{ __('store.Email Address') }}"
                    placeholder="{{ __('store.Enter your email here ') }}"
                    type="email"
                    field="email"
                    :is-required="true"
                />

                <x-general.button-black-animation class="!w-fit !py-2 px-6">
                    <button class="relative z-10" type="submit">
                        {{ __("dashboard.Send Reset Link") }}
                    </button>
                </x-general.button-black-animation>
            </form>
        </div>

        <div class="mt-6">
            <a
                href="{{ route("login") }}"
                class="flex items-center gap-x-2 text-sm text-gray-600"
            >
                <x-icons.back-to-store class="h-4 w-4" />
                {{ __("Back to Login") }}
            </a>
        </div>
    </main>
</x-store-main-layout>
