<div>
    @if ($success)
        <div class="mb-6 rounded-lg bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg
                        class="h-5 w-5 text-green-400"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">
                        {{ __("store.Thank you for your message") }}
                    </h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>
                            {{ __("store.We have received your inquiry and will respond as                             soon as possible") }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form
        wire:submit="submitForm"
        x-data="{
    async initTurnstile() {


            // Wait for turnstile to be fully loaded
            await new Promise(resolve => {
                if (document.readyState === 'complete') {
                    resolve();
                } else {
                    window.addEventListener('load', resolve);
                }
            });
            turnstile.render('#turnstile-container', {
                sitekey: '{{ config('services.cloudflare.site_key') }}',
                theme: 'light',
                action: 'contactform',
                size: 'normal',
                callback: (token) => {
                    @this.set('turnstileToken', token);
                    // Submit the form automatically when token is received
                }
            });

    }
    }"
        x-init="initTurnstile()"
    >
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">
            <x-checkout.input-field
                label="{{ __('store.Name') }}:"
                place-holder="{{ __('store.Type your name') }}"
                field="name"
                wire:model="name"
                :error="$errors->first('name')"
            />

            <x-checkout.input-field
                label="{{ __('store.Email') }}:"
                :required="true"
                place-holder="{{ __('store.Type you email') }}"
                field="email"
                type="email"
                wire:model="email"
                :error="$errors->first('email')"
            />
        </div>
        <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">
            <x-checkout.input-field
                label="{{ __('dashboard.Phone number') }}:"
                :required="true"
                place-holder="{{ __('dashboard.Type your phone number') }}:"
                field="phone_number"
                wire:model="phone_number"
                :error="$errors->first('phone_number')"
            />

            <x-checkout.input-field
                label="{{ __('dashboard.Subject') }}:"
                :required="true"
                place-holder="{{ __('dashboard.Email you used during checkout') }}"
                field="subject"
                wire:model="subject"
                :error="$errors->first('subject')"
            />
        </div>
        <x-checkout.text-area-field
            label="{{ __('store.Message') }}:"
            :required="true"
            placeHolder="{{ __('dashboard.Type your message') }}"
            field="message"
            wire:model="message"
        />
        @error("message")
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror

        @error("turnstileToken")
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror

        <!-- Invisible Turnstile container -->
        <div id="turnstile-container" class="hidden"></div>
        <button type="submit" class="mt-6 w-full" wire:loading.attr="disabled">
            <x-general.button-black-animation>
                <span class="relative z-10 inline-block" wire:loading.remove>
                    {{ __("store.Send Message") }}
                </span>
                <span class="relative z-10 inline-block" wire:loading>
                    {{ __("store.Sending contact") }}
                </span>
            </x-general.button-black-animation>
        </button>
    </form>

    @push("scripts")
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('fb-event', (eventData) => {
                    if (eventData.type === 'Lead') {
                        window.dataLayer = window.dataLayer || [];
                        window.dataLayer.push({
                            event: 'Lead',
                            ...eventData.params,
                        });
                    }
                });
            });
        </script>
    @endpush
</div>
