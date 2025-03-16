<x-store-main-layout>
    <main class="flex h-full w-full flex-col items-center justify-center py-20">
        <h1 class="text-center text-4xl font-bold">
            {{ __("Verify Your Email Address") }}
        </h1>
        <div
            class="mt-6 w-[80%] rounded-2xl p-4 md:w-[50%] md:p-8 lg:w-[30%]"
            style="box-shadow: 0 0 38px 14px rgba(2, 8, 53, 0.06)"
        >
            @if (session("status") == "verification-link-sent")
                <div class="mb-4 text-center text-sm text-green-600">
                    {{ __("store.A new verification link has been sent to your email address.") }}
                </div>
            @endif

            <div class="text-center">
                <p class="mb-4">
                    {{ __("Before proceeding, please check your email for a verification link.") }}
                </p>
                <p class="mb-4">
                    {{ __("If you did not receive the email") }}
                </p>

                <form
                    class="flex items-center justify-center"
                    method="POST"
                    action="{{ route("verification.send") }}"
                >
                    @csrf
                    <x-general.button-black-animation class="!w-fit !py-2 px-6">
                        <button type="submit" class="relative z-10">
                            {{ __("Resend Verification Email") }}
                        </button>
                    </x-general.button-black-animation>
                </form>
            </div>
        </div>

        <form method="POST" action="{{ route("logout") }}" class="mt-4">
            @csrf
            <x-general.button-white-animation
                class="!w-fit border !border-black !py-2 px-8 !normal-case"
            >
                <button type="submit" class="relative z-10">
                    {{ __("Log Out") }}
                </button>
            </x-general.button-white-animation>
        </form>
    </main>
    @php
        $user = Auth::user();
    @endphp

    @if ($user and $user->created_at->isToday())
        @push("scripts")
            <script>
                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push({
                    event: 'CompleteRegistration',
                });
            </script>
        @endpush
    @endif
</x-store-main-layout>
