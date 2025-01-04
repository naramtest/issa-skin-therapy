<div class="padding-from-side-menu bg-lightColor py-12">
    <form wire:submit="placeOrder" class="mx-auto">
        <div class="grid grid-cols-1 gap-x-6 lg:grid-cols-[56%_auto]">
            <!-- Left Column - Information Form -->
            <div class="rounded-2xl border bg-white p-8">
                <h2 class="mb-6 text-xl font-semibold">
                    {{ __("store.Information") }}
                </h2>
                {{-- TODO: fix labels --}}
                <!-- Personal Information Section -->
                <div class="space-y-8">
                    <div class="grid grid-cols-2 gap-4">
                        <x-checkout.input-field
                            label="{{ __('store.First Name') }}"
                            wire:model="form.billing_first_name"
                            place-holder="{{ __('store.First Name') }}"
                            required
                            field="form.billing_first_name"
                            :error="$errors->first('form.billing_first_name')"
                        />

                        <x-checkout.input-field
                            label="{{ __('store.Last Name') }}"
                            wire:model="form.billing_last_name"
                            required
                            place-holder="{{ __('store.Last Name') }}"
                            field="form.billing_last_name"
                            :error="$errors->first('form.billing_last_name')"
                        />
                    </div>

                    <x-checkout.input-field
                        label="Phone"
                        wire:model="form.phone"
                        required
                        place-holder="Phone"
                        field="form.phone"
                        :error="$errors->first('form.phone')"
                        helper-text="{{ __('store.Use english letters') }}"
                    />

                    <x-checkout.input-field
                        label="Email Address"
                        wire:model="form.email"
                        required
                        place-holder="Email Address"
                        field="form.email"
                        :error="$errors->first('form.email')"
                        type="email"
                    />

                    <!-- Billing Address Section -->
                    <x-checkout.select-field
                        label="Country / Region"
                        wire:model.live="form.billing_country"
                        required
                        field="form.billing_country"
                        :options="['Romania','Syria']"
                        :error="$errors->first('form.billing_country')"
                    />
                    <x-checkout.select-field
                        label="State / Region"
                        wire:model="form.billing_state"
                        required
                        field="form.billing_state"
                        :options="['Romania','Syria']"
                        :error="$errors->first('form.billing_state')"
                    />

                    <x-checkout.input-field
                        label="City"
                        wire:model="form.billing_city"
                        required
                        place-holder="City"
                        field="form.billing_city"
                        :error="$errors->first('form.billing_city')"
                    />

                    <x-checkout.input-field
                        label="Street Address"
                        wire:model="form.billing_address"
                        required
                        place-holder="Street Address"
                        field="form.billing_address"
                        :error="$errors->first('form.billing_address')"
                    />

                    <x-checkout.input-field
                        label="Area/Block"
                        wire:model="form.billing_area"
                        required
                        place-holder="Area and/or Block Number"
                        field="form.billing_area"
                        :error="$errors->first('form.billing_area')"
                    />

                    <x-checkout.input-field
                        label="Postal Code"
                        wire:model="form.billing_postal_code"
                        required
                        place-holder="{{ __('store.Postal Code') }}"
                        field="form.billing_postal_code"
                        :error="$errors->first('form.billing_postal_code')"
                        helper-text="{{ __('store.Please type') }}"
                    />

                    <x-checkout.input-field
                        label="Building"
                        wire:model="form.billing_building"
                        required
                        place-holder="Building Name/Number"
                        field="form.billing_building"
                        :error="$errors->first('form.billing_building')"
                    />

                    <x-checkout.input-field
                        label="Flat/Unit"
                        wire:model="form.billing_flat"
                        required
                        place-holder="Flat/Unit Number"
                        field="form.billing_flat"
                        :error="$errors->first('form.billing_flat')"
                    />
                </div>

                <!-- Additional Options -->
                <div class="mt-8 space-y-4">
                    @guest
                        <label class="flex items-center gap-2">
                            <input
                                type="checkbox"
                                wire:model="form.create_account"
                                class="rounded border-gray-300"
                            />
                            <span class="text-sm">
                                {{ __("store.Create an account?") }}
                            </span>
                        </label>
                    @endguest

                    <label class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            wire:model.live="form.different_shipping_address"
                            class="rounded border-gray-300"
                        />
                        <span class="text-sm">
                            {{ __("store.Ship to a different address?") }}
                        </span>
                    </label>
                </div>

                <!-- Shipping Address Section (Conditional) -->
                @if ($form->different_shipping_address)
                    <div class="mt-8 space-y-6 border-t pt-6">
                        <div class="grid grid-cols-2 gap-4">
                            <x-checkout.input-field
                                label="First Name"
                                wire:model="form.shipping_first_name"
                                place-holder="First Name"
                                required
                                field="form.shipping_first_name"
                                :error="$errors->first('form.shipping_first_name')"
                            />

                            <x-checkout.input-field
                                label="Last Name"
                                wire:model="form.shipping_last_name"
                                required
                                place-holder="Last Name"
                                field="form.shipping_last_name"
                                :error="$errors->first('form.shipping_last_name')"
                            />
                        </div>

                        <x-checkout.select-field
                            label="Country / Region"
                            wire:model.live="form.shipping_country"
                            required
                            field="form.shipping_country"
                            :options="['Romania','Syria']"
                            :error="$errors->first('form.shipping_country')"
                        />

                        <x-checkout.select-field
                            label="State / Region"
                            wire:model="form.shipping_state"
                            required
                            field="form.shipping_state"
                            :options="['Romania','Syria']"
                            :error="$errors->first('form.shipping_state')"
                        />

                        <x-checkout.input-field
                            label="City"
                            wire:model="form.shipping_city"
                            required
                            place-holder="City"
                            field="form.shipping_city"
                            :error="$errors->first('form.shipping_city')"
                        />

                        <x-checkout.input-field
                            label="Street Address"
                            wire:model="form.shipping_address"
                            required
                            place-holder="Street Address"
                            field="form.shipping_address"
                            :error="$errors->first('form.shipping_address')"
                        />

                        <x-checkout.input-field
                            label="Area/Block"
                            wire:model="form.shipping_area"
                            required
                            place-holder="Area and/or Block Number"
                            field="form.shipping_area"
                            :error="$errors->first('form.shipping_area')"
                        />

                        <x-checkout.input-field
                            label="Postal Code"
                            wire:model="form.shipping_postal_code"
                            required
                            place-holder="Postal Code"
                            field="form.shipping_postal_code"
                            :error="$errors->first('form.shipping_postal_code')"
                            helper-text="Type *000* if unknown"
                        />

                        <x-checkout.input-field
                            label="Building"
                            wire:model="form.shipping_building"
                            required
                            place-holder="Building Name/Number"
                            field="form.shipping_building"
                            :error="$errors->first('form.shipping_building')"
                        />

                        <x-checkout.input-field
                            label="Flat/Unit"
                            wire:model="form.shipping_flat"
                            required
                            place-holder="Flat/Unit Number"
                            field="form.shipping_flat"
                            :error="$errors->first('form.shipping_flat')"
                        />
                    </div>
                @endif

                <!-- Order Notes -->
                <div class="mt-8">
                    <x-checkout.text-area-field
                        label="Order Notes"
                        wire:model="form.order_notes"
                        field="form.order_notes"
                        place-holder="Notes about your order, e.g. special notes for delivery."
                    />
                </div>
                <!-- Error Messages -->
                @error("order")
                    <div class="mt-4 rounded-lg bg-red-50 p-4 text-red-600">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Right Column - Order Summary -->
            <div class="relative rounded-lg">
                <div class="sticky top-[90px]">
                    <div class="rounded-2xl bg-[#F5F5F5] p-8">
                        <h2 class="mb-6 text-lg font-semibold">
                            {{ __("store. Order Summary") }}
                        </h2>
                        <table class="w-full">
                            <thead>
                                <tr class="text-lg">
                                    <th
                                        class="pb-4 text-start font-normal text-[#69727d]"
                                    >
                                        {{ __("store.Products") }}
                                    </th>
                                    <th
                                        class="pb-4 text-end font-normal text-[#69727d]"
                                    >
                                        {{ __("store.Subtotal") }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="">
                                @foreach ($this->cartItems as $item)
                                    <tr class="text-sm">
                                        <td class="py-2">
                                            <div class="flex items-center">
                                                <h4>
                                                    {{ $item->getPurchasable()->name }}
                                                </h4>
                                                <p class="ms-3 text-gray-700">
                                                    ×
                                                    {{ $item->getQuantity() }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-2 text-end">
                                            <bdi>
                                                {{ $item->getSubtotal() }}
                                            </bdi>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="divide-y text-[#69727d]">
                                <tr>
                                    <td class="py-4">
                                        {{ __("store.Subtotal") }}
                                    </td>
                                    <td class="py-4 text-right">
                                        <x-price :money="$this->subtotal" />
                                    </td>
                                </tr>
                                <tr>
                                    <td class="py-4">
                                        {{ __("store.Shipping") }}
                                    </td>
                                    <td class="py-4 text-right text-darkColor">
                                        {{ __("store.Free shipping") }}
                                    </td>
                                </tr>
                                <tr class="font-medium">
                                    <td class="py-4">
                                        {{ __("store.Total") }}
                                    </td>
                                    <td class="py-4 text-right">
                                        <x-price :money="$this->total" />
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Coupon Code -->
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

                    <!-- Payment -->
                    <div class="mt-8 rounded-[15px] border p-8">
                        <div class="mb-4 flex items-center gap-2">
                            <input type="radio" name="payment" checked />
                            <label>Credit/Debit Cards</label>
                            <div class="ml-auto flex gap-2">
                                <img
                                    src="/path-to-amex"
                                    alt="Amex"
                                    class="h-6"
                                />
                                <img
                                    src="/path-to-mastercard"
                                    alt="Mastercard"
                                    class="h-6"
                                />
                                <img
                                    src="/path-to-visa"
                                    alt="Visa"
                                    class="h-6"
                                />
                            </div>
                        </div>

                        <!-- Card Input -->
                        <div class="flex rounded-lg border border-gray-200">
                            <input
                                type="text"
                                class="flex-1 border-r border-gray-200 p-3 text-sm focus:outline-none"
                                placeholder="Card number"
                            />
                            <input
                                type="text"
                                class="w-24 border-r border-gray-200 p-3 text-sm focus:outline-none"
                                placeholder="MM/YY"
                            />
                            <input
                                type="text"
                                class="w-20 p-3 text-sm focus:outline-none"
                                placeholder="CVC"
                            />
                        </div>

                        <!-- Terms -->
                        <div class="mt-16">
                            <p class="text-sm text-gray-600">
                                {{ __("store.Your personal data will be used to process your                                 order, support your experience throughout this                                 website, and for other purposes described in our") }}
                                <a
                                    href="{{ route("privacy.index") }}"
                                    class="text-blue-600 hover:underline"
                                >
                                    {{ __("store.privacy policy") }}
                                </a>
                                .
                            </p>
                            <div class="mt-8">
                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        wire:model="form.terms_accepted"
                                        class="rounded border-gray-300"
                                    />
                                    <span class="text-sm">
                                        I have read and agree to the website
                                        <a
                                            href="{{ route("terms.index") }}"
                                            class="text-blue-600 hover:underline"
                                        >
                                            {{ __("store.terms and conditions") }}
                                        </a>
                                        <span class="text-red-500">*</span>
                                    </span>
                                </label>
                                @error("form.terms_accepted")
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Place Order Button -->
                        <button type="submit" class="w-full">
                            <x-general.button-black-animation class="mt-8">
                                <span
                                    wire:loading.remove
                                    class="relative z-10 inline-block"
                                >
                                    {{ __("store.Place order") }}
                                </span>
                                <span wire:loading>Processing...</span>
                            </x-general.button-black-animation>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
