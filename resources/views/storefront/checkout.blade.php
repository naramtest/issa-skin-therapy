<x-store-main-layout>
    <div class="padding-from-side-menu bg-lightColor py-12">
        <div class="mx-auto">
            <div class="grid grid-cols-1 gap-x-6 lg:grid-cols-[56%_auto]">
                <!-- Left Column - Information Form -->
                <x-checkout.sections.form />

                <!-- Right Column - Order Summary -->
                <div class="rounded-lg">
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
                                @foreach ($cartItems as $item)
                                    <tr class="text-sm">
                                        <td class="py-2">
                                            <div class="flex items-center">
                                                <h4>
                                                    {{ $item["name"] }}
                                                </h4>
                                                <p class="ms-3 text-gray-700">
                                                    × {{ $item["quantity"] }}
                                                </p>
                                            </div>
                                        </td>
                                        <td class="py-2 text-end">
                                            <bdi>
                                                €{{ number_format($item["price"], 2) }}
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
                                        <bdi>€582.68</bdi>
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
                                        <bdi>€582.68</bdi>
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
                                    href="#"
                                    class="text-blue-600 hover:underline"
                                >
                                    {{ __("store.privacy policy") }}
                                </a>
                                .
                            </p>
                            <label class="mt-4 flex items-center gap-2">
                                <input
                                    type="checkbox"
                                    class="rounded border-gray-300"
                                />
                                <span class="text-sm">
                                    {{ __("store. I have read and agree to the website") }}
                                    <a
                                        href="#"
                                        class="text-blue-600 hover:underline"
                                    >
                                        {{ __("store.terms and conditions") }}
                                    </a>
                                    <span class="text-red-500">*</span>
                                </span>
                            </label>
                        </div>

                        <!-- Place Order Button -->
                        <button
                            class="mt-6 w-full rounded-lg bg-[#1f1f1f] py-4 text-center text-white hover:bg-[#2f2f2f]"
                        >
                            {{ __("store.Place order") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-store-main-layout>
