@props([
    /**@var\App\Models\User*/"user",
])

<x-store-main-layout>
    <main class="mx-auto min-h-[100vh] w-[75%] pb-10 pt-10">
        <h1 class="text-5xl font-bold md:text-[95px] rtl:text-5xl">
            {{ __("store.My account") }}
        </h1>
        <div class="mt-4 flex items-center gap-x-6 md:ms-2 rtl:mt-8">
            <x-general.button-white-animation
                class="!border-black !py-2 px-1 !normal-case md:!w-fit md:px-8"
            >
                <a
                    href="{{ route("account.edit") }}"
                    class="relative z-[10] text-xs lg:text-base"
                >
                    {{ __("store.User Information") }}
                </a>
            </x-general.button-white-animation>
            {{-- <x-general.button-white-animation --}}
            {{-- class="!w-fit border !border-black !py-2 px-8 !normal-case" --}}
            {{-- > --}}
            {{-- <a --}}
            {{-- href="{{ route("login.index") }}" --}}
            {{-- class="relative z-[10]" --}}
            {{-- type="submit" --}}
            {{-- > --}}
            {{-- {{ __("store.Sign In") }} --}}
            {{-- </a> --}}
            {{-- </x-general.button-white-animation> --}}
            {{-- <x-general.button-white-animation --}}
            {{-- class="!w-fit border !border-black !py-2 px-8 !normal-case" --}}
            {{-- > --}}
            {{-- <a --}}
            {{-- href="{{ route("login.index") }}" --}}
            {{-- class="relative z-[10]" --}}
            {{-- type="submit" --}}
            {{-- > --}}
            {{-- {{ __("store.Sign In") }} --}}
            {{-- </a> --}}
            {{-- </x-general.button-white-animation> --}}
            <form action="{{ route("logout") }}" method="post">
                @csrf
                <x-general.button-white-animation
                    class="!w-[max-content] !border-black !py-2 px-4 !normal-case md:!w-fit md:px-8"
                >
                    <button
                        class="relative z-[10] text-xs md:text-base"
                        type="submit"
                    >
                        {{ __("store.Log out") }}
                    </button>
                </x-general.button-white-animation>
            </form>
        </div>
        {{ $slot }}

        <a
            href="{{ route("storefront.index") }}"
            class="mt-20 flex items-center justify-center gap-x-2"
        >
            <x-icons.back-to-store class="h-4 w-4" />
            <p>{{ __("store.Back To Store") }}</p>
        </a>
    </main>
</x-store-main-layout>
