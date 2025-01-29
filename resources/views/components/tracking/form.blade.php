<div class="mx-auto max-w-6xl">
    <p>
        {{ __("store.To track your order please enter your Order ID in the box below") }}
    </p>
    <div class="my-6 flex w-full justify-between">
        <div class="w-[45%]">
            <label class="text-sm font-medium" for="orderId">
                {{ __("store.Order ID") }}
            </label>
            <input
                class="mt-[6px] block w-full border border-gray-300 px-2 py-2"
                type="text"
                name="orderId"
                id="orderId"
                placeholder="Found in your order confirmation email"
                required
                wire:model="orderId"
            />
            @error("orderId")
                <p class="ms-1 mt-1 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>
        <div class="w-[45%]">
            <label class="text-sm font-medium" for="billing_email">
                {{ __("store.Billing") }} {{ __("store.Email") }}
            </label>
            <input
                class="mt-[6px] block w-full border border-gray-300 px-2 py-2"
                type="email"
                name="billing_email"
                id="billing_email"
                placeholder="Email you used during checkout"
                required
                wire:model="email"
            />
            @error("email")
                <p class="ms-1 mt-1 text-sm text-red-500">
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>
    <button wire:click="trackOrder()">
        <x-general.button-black-animation class="rounded-md !py-3 px-4">
            <span class="relative z-[10]">{{ __("store.track") }}</span>
        </x-general.button-black-animation>
    </button>
</div>
