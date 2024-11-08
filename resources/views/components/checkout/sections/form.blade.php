<div class="rounded-2xl border bg-white p-8">
    <h2 class="mb-6 text-xl font-semibold">
        {{ __("store.Information") }}
    </h2>
    {{-- TODO:change types when needed --}}
    <form
        x-data="{
            createAccount: false,
            differentAddress: false,
        }"
    >
        <!-- Name Fields -->
        <div class="mb-6 grid grid-cols-2 gap-4">
            <x-checkout.input-field
                label="First Name"
                :required="true"
                place-holder="First Name"
                field="first_name"
            />

            <x-checkout.input-field
                label="Last Name"
                :required="true"
                place-holder="Last Name"
                field="last_name"
            />
        </div>

        <!-- Phone -->
        <div class="mb-6">
            <x-checkout.input-field
                label="Phone"
                :required="true"
                place-holder="Phone"
                field="phone"
            />
            <p class="mt-1 text-xs text-gray-500">
                {{ __("store.Use english letters") }}
            </p>
        </div>

        <!-- Email -->
        <x-checkout.input-field
            class="mb-6"
            label="Email Address"
            place-holder="Email Address"
            field="email"
        />

        <!-- Country/Region -->
        <x-checkout.select-field
            class="mb-6"
            label="Country / Region"
            field="country"
            :options="['Romania','Syria']"
        />

        <!-- State/County -->
        <x-checkout.select-field
            class="mb-6"
            label="State / County"
            field="state"
            :options="['Romania','Syria']"
        />

        <!-- Town/City -->

        <x-checkout.select-field
            class="mb-6"
            label="Town / City"
            field="city"
            :options="['Romania','Syria']"
        />

        <!-- Postcode -->
        <div class="mb-6">
            <x-checkout.input-field
                label="Postcode / ZIP"
                :required="true"
                place-holder="Postcode / ZIP"
                field="postcode"
            />
            <p class="mt-1 text-xs text-gray-500">
                {{ __("store. Please type *000* if postcode is unknown") }}
            </p>
        </div>
        <!-- Street Address -->
        <x-checkout.input-field
            class="mb-6"
            label="Street Address"
            :required="true"
            place-holder="Street Address"
            field="street"
        />

        <!-- Area/Block Number -->
        <x-checkout.input-field
            class="mb-6"
            label="Area and/or Block Number"
            :required="true"
            place-holder="Area and/or Block Number"
            field="area"
        />

        <!-- Building Name/Number -->
        <x-checkout.input-field
            class="mb-6"
            label="Building Name/Number"
            :required="true"
            place-holder="Building Name/Number"
            field="building"
        />

        <!-- Flat/Home Number -->
        <x-checkout.input-field
            class="mb-6"
            label="Flat/Home Number"
            :required="true"
            place-holder="Flat/Home Number"
            field="flat"
        />

        {{-- TODO: add fields for different shipping address --}}
        <!-- Checkboxes -->
        <div class="space-y-4">
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    x-model="createAccount"
                    class="rounded border-gray-300"
                />
                <span class="text-sm">
                    {{ __("store.Create an account?") }}
                </span>
            </label>
            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    x-model="differentAddress"
                    class="rounded border-gray-300"
                />
                <span class="text-sm">
                    {{ __("store.Ship to a different address?") }}
                </span>
            </label>
        </div>

        <!-- Order Notes -->
        <div class="mt-6">
            <label class="mb-1 block font-[700] text-[#69727d]">
                Order Notes (optional)
            </label>
            <textarea
                class="w-full rounded-lg bg-[#F4F4F4] p-3 text-sm focus:border-gray-300 focus:outline-none"
                rows="4"
                placeholder="Notes about your order, e.g. special notes for delivery."
            ></textarea>
        </div>
    </form>
</div>
