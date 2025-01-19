@props([
    "couponError" => null,
    "form",
])

<div class="mt-4 rounded-[15px] border p-8">
    <p class="mb-6">{{ __("store.If you have a coupon code") }}</p>
    <div class="flex gap-2">
        <label for="coupon" class="sr-only">
            {{ __("store.Coupon Code") }}
        </label>

        <input
            wire:model="form.coupon_code"
            class="w-full rounded-[11px] border-none bg-[#F4F4F4] px-7 py-5 text-sm text-[#69727d] focus:outline-none"
            name="coupon"
            id="coupon"
            placeholder="{{ __("store.Coupon Code") }}"
        />
        <button
            wire:click="applyCoupon"
            type="button"
            class="rounded-3xl bg-[#1f1f1f] px-6 py-3 text-sm text-white hover:bg-[#2f2f2f]"
        >
            {{ __("store.Apply") }}
        </button>
    </div>

    @if ($couponError)
        <p class="mt-2 text-sm text-red-600">{{ $couponError }}</p>
    @endif

    @if ($this->discount)
        <div class="mt-4 flex items-center justify-between">
            <span class="text-sm text-gray-600">
                {{ __("store.Coupon applied") }} : {{ $form->coupon_code }}
            </span>
            <button
                wire:click="removeCoupon"
                type="button"
                class="text-sm text-red-600 hover:text-red-800"
            >
                {{ __("store.Remove") }}
            </button>
        </div>
    @endif
</div>
