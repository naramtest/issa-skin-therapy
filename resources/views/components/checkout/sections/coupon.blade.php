<div class="mt-4 rounded-[15px] border p-8">
    <p class="mb-6">
        {{ __("store. If you have a coupon code") }}
    </p>
    <div class="flex gap-2">
        <label for="coupon" class="sr-only">
            {{ __("store.Coupon Code") }}
        </label>

        <input
            class="w-full rounded-[11px] border-none bg-[#F4F4F4] px-7 py-5 text-sm text-[#69727d] focus:outline-none"
            name="coupon"
            id="coupon"
            placeholder="{{ __("store.Coupon Code") }}"
            value="{{ old("coupon") }}"
        />
        <button
            class="rounded-3xl bg-[#1f1f1f] px-6 py-3 text-sm text-white hover:bg-[#2f2f2f]"
        >
            {{ __("store.Apply") }}
        </button>
    </div>
</div>
