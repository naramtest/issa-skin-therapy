{{-- TODO: finish today --}}
<x-store-main-layout>
    <main class="mx-auto max-w-4xl px-4 pt-16 sm:px-6 lg:px-8">
        <!-- Success Header -->
        <div class="rounded-lg bg-[#1A1A1A] p-8 text-center">
            <div class="mb-4 flex justify-center">
                <div
                    class="flex h-16 w-16 items-center justify-center rounded-full bg-white"
                >
                    <svg
                        class="h-8 w-8 text-green-500"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-white sm:text-4xl">
                Thank you. Your order has been received.
            </h1>
        </div>

        <!-- Order Information -->
        <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg bg-gray-50 p-6">
                <h2 class="text-sm font-medium text-gray-500">ORDER NUMBER:</h2>
                <p class="mt-2 text-lg font-medium">
                    {{ $order->order_number }}
                </p>
            </div>

            <div class="rounded-lg bg-gray-50 p-6">
                <h2 class="text-sm font-medium text-gray-500">DATE:</h2>
                <p class="mt-2 text-lg font-medium">
                    {{ $order->created_at->format("F j, Y") }}
                </p>
            </div>

            <div class="rounded-lg bg-gray-50 p-6">
                <h2 class="text-sm font-medium text-gray-500">TOTAL:</h2>
                <p class="mt-2 text-lg font-medium">
                    {{ \App\Services\Currency\CurrencyHelper::format($order->getMoneyTotal()) }}
                </p>
            </div>

            <div class="rounded-lg bg-gray-50 p-6">
                <h2 class="text-sm font-medium text-gray-500">
                    PAYMENT METHOD:
                </h2>
                <p class="mt-2 text-lg font-medium">
                    {{ strtoupper($order->payment_method_details["brand"] ?? "") }}
                    @if (isset($order->payment_method_details["last4"]))
                        ENDING IN
                        {{ $order->payment_method_details["last4"] }}
                    @endif
                </p>
            </div>
        </div>

        <!-- Order Details -->
        <div class="mt-12">
            <h2 class="text-2xl font-bold">Order Details</h2>

            <div class="mt-6 overflow-hidden rounded-lg border">
                <!-- Header -->
                <div class="bg-[#1A1A1A] px-6 py-4">
                    <div class="grid grid-cols-2">
                        <div class="text-left text-sm font-medium text-white">
                            Product
                        </div>
                        <div class="text-right text-sm font-medium text-white">
                            Total
                        </div>
                    </div>
                </div>

                <!-- Items -->
                <div class="divide-y divide-gray-200 bg-white">
                    @foreach ($order->items as $item)
                        <div class="grid grid-cols-2 px-6 py-4">
                            <div>
                                <h3 class="text-sm font-medium">
                                    {{ $item->purchasable->name }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Qty: {{ $item->quantity }}
                                </p>
                            </div>
                            <div class="text-right">
                                {{ \App\Services\Currency\CurrencyHelper::format($item->getMoneySubtotal()) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Summary -->
                <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                    <dl class="space-y-4">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">
                                Subtotal:
                            </dt>
                            <dd class="text-sm font-medium">
                                {{ \App\Services\Currency\CurrencyHelper::format($order->getMoneySubtotal()) }}
                            </dd>
                        </div>
                        {{-- TODO: show if an item has a discount --}}
                        {{-- @if ($order->discount) --}}
                        {{-- <div class="flex justify-between"> --}}
                        {{-- <dt class="text-sm font-medium text-gray-500"> --}}
                        {{-- Discount: --}}
                        {{-- </dt> --}}
                        {{-- <dd class="text-sm font-medium text-red-600"> --}}
                        {{-- -{{ \App\Services\Currency\CurrencyHelper::format($order->getMoneyDiscount()) }} --}}
                        {{-- </dd> --}}
                        {{-- </div> --}}
                        {{-- @endif --}}

                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">
                                Shipping:
                            </dt>
                            <dd class="text-sm font-medium">
                                {{
                                    $order->shipping_cost > 0
                                        ? \App\Services\Currency\CurrencyHelper::format($order->money_shipping_cost)
                                        : "Free shipping"
                                }}
                            </dd>
                        </div>

                        <div
                            class="flex justify-between border-t border-gray-200 pt-4"
                        >
                            <dt class="text-base font-medium">Total:</dt>
                            <dd class="text-base font-medium">
                                {{ \App\Services\Currency\CurrencyHelper::format($order->getMoneyTotal()) }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Addresses -->
        <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2">
            <!-- Billing Address -->
            <div class="rounded-lg bg-gray-50 p-6">
                <h2 class="text-lg font-medium">Billing Address</h2>
                <address class="mt-4 not-italic">
                    <p class="text-sm">
                        {{ $order->billingAddress->full_name }}
                    </p>
                    <p class="mt-2 text-sm">
                        {{ $order->billingAddress->address }}
                        <br />
                        @if ($order->billingAddress->flat)
                            Flat: {{ $order->billingAddress->flat }}
                            <br />
                        @endif

                        {{ $order->billingAddress->city }}
                        <br />
                        {{ $order->billingAddress->state }}
                        <br />
                        {{ $order->billingAddress->country }}
                        {{ $order->billingAddress->postal_code }}
                    </p>
                    <p class="mt-2 text-sm">
                        {{ $order->billingAddress->phone }}
                    </p>
                    <p class="mt-2 text-sm">{{ $order->email }}</p>
                </address>
            </div>

            <!-- Shipping Address -->
            <div class="rounded-lg bg-gray-50 p-6">
                <h2 class="text-lg font-medium">Shipping Address</h2>
                <address class="mt-4 not-italic">
                    <p class="text-sm">
                        {{ $order->shippingAddress->full_name }}
                    </p>
                    <p class="mt-2 text-sm">
                        {{ $order->shippingAddress->address }}
                        <br />
                        @if ($order->shippingAddress->flat)
                            Flat: {{ $order->shippingAddress->flat }}
                            <br />
                        @endif

                        {{ $order->shippingAddress->city }}
                        <br />
                        {{ $order->shippingAddress->state }}
                        <br />
                        {{ $order->shippingAddress->country }}
                        {{ $order->shippingAddress->postal_code }}
                    </p>
                </address>
            </div>
        </div>

        <!-- Return to Store Button -->
        <div class="mt-12 text-center">
            <a
                href="{{ route("shop.index") }}"
                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-gray-800"
            >
                <span>Return to store</span>
                <svg
                    class="ml-2 h-4 w-4"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3"
                    />
                </svg>
            </a>
        </div>
    </main>
</x-store-main-layout>
