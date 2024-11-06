<x-store-main-layout>
    <div class="padding-from-side-menu bg-lightColor py-12">
        <div class="mx-auto">
            <div class="grid grid-cols-1 gap-x-6 lg:grid-cols-[56%_auto]">
                <!-- Left Column - Information Form -->
                <x-checkout.sections.form />

                <!-- Right Column - Order Summary -->
                <div class="rounded-lg bg-white p-8 shadow-sm">
                    <h2 class="mb-6 text-xl font-semibold">Order Summary</h2>

                    <!-- Products List -->
                    <div class="space-y-4">
                        @foreach ($cartItems as $item)
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium">
                                        {{ $item["name"] }}
                                    </h4>
                                    <p class="text-sm text-gray-500">
                                        × {{ $item["quantity"] }}
                                    </p>
                                </div>
                                <p class="font-medium">
                                    €{{ number_format($item["price"], 2) }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Totals -->
                    <div class="mt-6 space-y-2 border-t pt-4">
                        <div class="flex justify-between">
                            <p>Subtotal</p>
                            <p>€582.68</p>
                        </div>
                        <div class="flex justify-between">
                            <p>Shipping</p>
                            <p class="text-green-600">Free shipping</p>
                        </div>
                        <div
                            class="flex justify-between border-t pt-2 font-medium"
                        >
                            <p>Total</p>
                            <p>€582.68</p>
                        </div>
                    </div>

                    <!-- Coupon Code -->
                    <div class="mt-8">
                        <p class="mb-3 text-sm">
                            If you have a coupon code, please apply it below.
                        </p>
                        <div class="flex gap-2">
                            <input
                                type="text"
                                class="flex-1 rounded-lg border border-gray-200 p-3 text-sm focus:border-gray-300 focus:outline-none"
                                placeholder="Coupon code"
                            />
                            <button
                                class="rounded-lg bg-[#1f1f1f] px-6 py-3 text-sm text-white hover:bg-[#2f2f2f]"
                            >
                                Apply
                            </button>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="mt-8">
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
                    </div>

                    <!-- Terms -->
                    <div class="mt-8">
                        <p class="text-sm text-gray-600">
                            Your personal data will be used to process your
                            order, support your experience throughout this
                            website, and for other purposes described in our
                            <a href="#" class="text-blue-600 hover:underline">
                                privacy policy
                            </a>
                            .
                        </p>
                        <label class="mt-4 flex items-center gap-2">
                            <input
                                type="checkbox"
                                class="rounded border-gray-300"
                            />
                            <span class="text-sm">
                                I have read and agree to the website
                                <a
                                    href="#"
                                    class="text-blue-600 hover:underline"
                                >
                                    terms and conditions
                                </a>
                                <span class="text-red-500">*</span>
                            </span>
                        </label>
                    </div>

                    <!-- Place Order Button -->
                    <button
                        class="mt-6 w-full rounded-lg bg-[#1f1f1f] py-4 text-center text-white hover:bg-[#2f2f2f]"
                    >
                        Place order
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-store-main-layout>
