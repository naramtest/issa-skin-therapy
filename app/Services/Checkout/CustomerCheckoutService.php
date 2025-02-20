<?php

namespace App\Services\Checkout;

use App\Data\Orders\CreateOrderData;
use App\Enums\AddressType;
use App\Enums\Checkout\DHLProduct;
use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerEmail;
use App\Models\Order;
use App\Models\State;
use App\Services\Cart\CartService;
use App\Services\Coupon\CouponService;
use DB;

readonly class CustomerCheckoutService
{
    public function __construct(
        private CartService $cartService,
        private OrderService $orderService,
        private CouponService $couponService
    ) {
    }

    public function processCheckout($validationData, array $data): Order
    {
        return DB::transaction(function () use ($data, $validationData) {
            // 1. Handle Customer
            $customer = $this->handleCustomer($validationData);
            // 2. Handle Addresses
            $billingAddress = $this->handleAddress(
                $customer,
                $validationData,
                AddressType::BILLING
            );
            $shippingAddress = $validationData["different_shipping_address"]
                ? $this->handleAddress(
                    $customer,
                    $validationData,
                    AddressType::SHIPPING
                )
                : $billingAddress;
            // 3. Create Order via OrderService
            $order = $this->orderService->createOrder(
                new CreateOrderData(
                    customerId: $customer->id,
                    email: $validationData["email"],
                    billingAddressId: $billingAddress->id,
                    shippingAddressId: $shippingAddress->id,
                    status: OrderStatus::PENDING,
                    paymentStatus: PaymentStatus::PENDING,
                    shippingMethod: $data["shipping_method"] ?? null,
                    subtotal: $this->cartService->getSubtotal()->getAmount(),
                    shippingCost: $data["shipping_cost"] ?? null,
                    total: $this->cartService->getTotal()->getAmount(),
                    notes: $validationData["notes"] ?? null,
                    cartItems: $this->cartService->getItems(),
                    dhlProduct: $data["dhl_product"] ??
                        DHLProduct::getProduct($shippingAddress->country)->value
                )
            );

            if ($coupon = $this->cartService->getAppliedCoupon()) {
                $this->couponService->recordUsage(
                    $coupon,
                    $order,
                    $order->customer,
                    $this->cartService->getCouponDiscount()
                );
            }

            return $order;
        });
    }

    private function handleCustomer(array $data): Customer
    {
        if (auth()->check()) {
            $user = auth()->user();

            // If user has no customer record, create one
            if (!$user->customer) {
                $user->customer()->create([
                    "email" => $user->email,
                    "is_registered" => true,
                    "first_name" => $user->first_name,
                    "last_name" => $user->last_name,
                    "name" => $user->name,
                    "orders_count" => 0,
                    "total_spent" => 0,
                ]);

                // Refresh user to get the new customer relation
                $user->refresh();
            }

            $customer = $user->customer;

            // Handle different email for registered user
            if ($data["email"] !== $customer->email) {
                CustomerEmail::firstOrCreate(
                    ["customer_id" => $customer->id, "email" => $data["email"]],
                    ["last_used_at" => now()]
                );
            }
        } else {
            // For guests, find or create customer
            $customer = Customer::firstOrCreate(
                [
                    "email" => $data["email"],
                    "is_registered" => false,
                ],
                [
                    "first_name" => $data["billing_first_name"],
                    "last_name" => $data["billing_last_name"],
                    "name" =>
                        $data["billing_first_name"] .
                        " " .
                        $data["billing_last_name"],
                    "orders_count" => 0,
                    "total_spent" => 0,
                ]
            );
        }

        // Update customer metrics
        $customer->update([
            "orders_count" => $customer->orders_count + 1,
            "last_order_at" => now(),
        ]);

        return $customer;
    }

    private function handleAddress(
        Customer $customer,
        array $validationData,
        AddressType $type
    ): CustomerAddress {
        $addressData = $this->getAddress($validationData, $type->value);
        // Try to find matching existing address
        $existingAddress = $customer
            ->addresses()
            ->where("type", $type)
            ->where("address", $addressData["address"])
            ->where("postal_code", $addressData["postal_code"])
            ->where("city", $addressData["city"])
            ->where("country", $addressData["country"])
            ->first();

        if ($existingAddress) {
            $existingAddress->update([
                "last_used_at" => now(),
                "phone" => $addressData["phone"],
                "first_name" => $addressData["first_name"],
                "last_name" => $addressData["last_name"],
                "state" => $addressData["state"] ?? null,
                "area" => $addressData["area"] ?? null,
                "building" => $addressData["building"] ?? null,
                "flat" => $addressData["flat"] ?? null,
                "is_default" => !$customer->addresses()->exists(),
            ]);
            return $existingAddress;
        }

        // Create new address
        return CustomerAddress::create([
            "customer_id" => $customer->id,
            "type" => $type,
            "first_name" => $addressData["first_name"],
            "last_name" => $addressData["last_name"],
            "phone" => $addressData["phone"],
            "address" => $addressData["address"],
            "city" => $addressData["city"],
            "state" => $addressData["state"] ?? null,
            "country" => $addressData["country"],
            "postal_code" => $addressData["postal_code"],
            "area" => $addressData["area"] ?? null,
            "building" => $addressData["building"] ?? null,
            "flat" => $addressData["flat"] ?? null,
            "is_default" => !$customer->addresses()->exists(),
            "last_used_at" => now(),
        ]);
    }

    /**
     * @param array $validatedData
     * @param string $type
     * @return array
     */
    public function getAddress(array $validatedData, string $type): array
    {
        return [
            "first_name" => $validatedData[$type . "_first_name"],
            "last_name" => $validatedData[$type . "_last_name"],
            "phone" => $validatedData["phone"],
            "address" => $validatedData[$type . "_address"],
            "city" => $validatedData[$type . "_city"],
            "state" =>
                State::find($validatedData[$type . "_state"])->name ?? null,
            "country" => $validatedData[$type . "_country"],
            "postal_code" => $validatedData[$type . "_postal_code"],
            "area" => $validatedData[$type . "_area"],
            "building" => $validatedData[$type . "_building"],
            "flat" => $validatedData[$type . "_flat"],
        ];
    }
}
