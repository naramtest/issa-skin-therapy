<x-store-main-layout>
    <div class="padding-from-side-menu py-12">
        <div class="mx-auto max-w-3xl">
            <!-- Success Message -->
            <div class="mb-8 text-center">
                <div
                    class="mb-4 inline-flex h-24 w-24 items-center justify-center rounded-full bg-green-100"
                >
                    <svg
                        class="h-12 w-12 text-green-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        ></path>
                    </svg>
                </div>
                <h1 class="mb-4 text-3xl font-bold">
                    Thank you for your order!
                </h1>
                <p class="text-lg text-gray-600">
                    Your order has been confirmed and will be shipped shortly.
                </p>
                <p class="mt-2 text-gray-600">
                    We have sent you an email with your order details and
                    tracking information.
                </p>
            </div>

            <!-- Order Information -->
            <div class="mb-8 overflow-hidden rounded-lg bg-white shadow">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold">Order Details</h2>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Order Number
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                #{{ $order->order_number }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Order Date
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $order->created_at->format("F j, Y") }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Email
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $order->email }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Order Status
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $order->status->getLabel() }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="mb-8 overflow-hidden rounded-lg bg-white shadow">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold">Order Summary</h2>
                </div>
                <div class="px-6 py-4">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b text-left">
                                <th
                                    class="pb-4 text-sm font-medium text-gray-500"
                                >
                                    Product
                                </th>
                                <th
                                    class="pb-4 text-right text-sm font-medium text-gray-500"
                                >
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr class="border-b">
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            <div>
                                                <div
                                                    class="text-sm font-medium text-gray-900"
                                                >
                                                    {{ $item->purchasable->name }}
                                                </div>
                                                <div
                                                    class="mt-1 text-sm text-gray-500"
                                                >
                                                    × {{ $item->quantity }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td
                                        class="py-4 text-right text-sm text-gray-500"
                                    >
                                        {{ $item->getMoneySubtotal() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-b">
                                <th
                                    class="py-4 text-sm font-medium text-gray-500"
                                >
                                    Subtotal
                                </th>
                                <td
                                    class="py-4 text-right text-sm text-gray-900"
                                >
                                    {{ $order->getMoneySubtotal() }}
                                </td>
                            </tr>
                            <tr class="border-b">
                                <th
                                    class="py-4 text-sm font-medium text-gray-500"
                                >
                                    Shipping
                                </th>
                                <td
                                    class="py-4 text-right text-sm text-gray-900"
                                >
                                    {{ $order->money_shipping_cost }}
                                </td>
                            </tr>
                            <tr>
                                <th
                                    class="py-4 text-base font-semibold text-gray-900"
                                >
                                    Total
                                </th>
                                <td
                                    class="py-4 text-right text-base font-semibold text-gray-900"
                                >
                                    {{ $order->getMoneyTotal() }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-center space-x-4">
                <!-- Invoice Download -->
                <a
                    href="{{ route("orders.invoice.download", $order) }}"
                    class="inline-flex items-center rounded-full border border-black bg-white px-6 py-3 text-sm font-medium text-black hover:bg-gray-50"
                >
                    <svg
                        class="mr-2 h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        ></path>
                    </svg>
                    Download Invoice
                </a>

                <!-- Continue Shopping -->
                <a
                    href="{{ route("shop.index") }}"
                    class="rounded-full bg-black px-6 py-3 text-sm font-medium text-white hover:bg-gray-800"
                >
                    Continue Shopping
                </a>

                <!-- View Orders (for logged in users) -->
                @auth
                    <a
                        href="{{ route("account.orders") }}"
                        class="rounded-full border border-black px-6 py-3 text-sm font-medium text-black hover:bg-gray-100"
                    >
                        View Orders
                    </a>
                @endauth
            </div>

            <!-- Guest Registration Prompt -->
            @if ($showRegistration)
                <div class="mt-8 rounded-lg bg-blue-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg
                                class="h-5 w-5 text-blue-400"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Create an Account
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>
                                    Create an account to track your orders and
                                    get faster checkout next time.
                                </p>
                            </div>
                            <div class="mt-4">
                                <div class="-mx-2 -my-1.5 flex">
                                    <a
                                        href="{{ route("register") }}"
                                        class="rounded-md bg-blue-800 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
                                    >
                                        Register Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-store-main-layout>
