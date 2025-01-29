<?php

namespace App\Services\Checkout;

use App\Enums\AddressType;
use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\CustomerEmail;
use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\Coupon\CouponService;
use DB;

readonly class CustomerCheckoutService
{
    private OrderService $orderService;
    private CouponService $couponService;

    public function __construct(private CartService $cartService)
    {
        $this->orderService = new OrderService();
        $this->couponService = new CouponService();
    }

    public function processCheckout(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            // 1. Handle Customer
            $customer = $this->handleCustomer($data);

            // 2. Handle Addresses
            $billingAddress = $this->handleAddress(
                $customer,
                $data["billing"],
                AddressType::BILLING
            );
            $shippingAddress = $data["different_shipping_address"]
                ? $this->handleAddress(
                    $customer,
                    $data["shipping"],
                    AddressType::SHIPPING
                )
                : $billingAddress;

            // 3. Create Order via OrderService
            $order = $this->orderService->createOrder([
                "customer_id" => $customer->id,
                "email" => $data["email"], // Store checkout email directly in order
                "billing_address_id" => $billingAddress->id,
                "shipping_address_id" => $shippingAddress->id,
                "status" => OrderStatus::PENDING,
                "payment_status" => PaymentStatus::PENDING,
                "shipping_method" => $data["shipping_method"] ?? null,
                "shipping_cost" => $data["shipping_cost"] ?? null,
                "notes" => $data["notes"] ?? null,
                "cart_items" => $this->cartService->getItems(),
                "subtotal" => $this->cartService->getSubtotal()->getAmount(),
                "total" => $this->cartService->getTotal()->getAmount(),
            ]);

            if ($coupon = $this->cartService->getAppliedCoupon()) {
                $this->couponService->recordUsage(
                    $coupon,
                    $order,
                    $order->customer,
                    $this->cartService->getCouponDiscount()
                );
            }

            // 4. Clear the cart after successful order creation
            //            $this->cartService->clear();

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

            return $customer;
        }

        // For guests, find or create customer
        $customer = Customer::firstOrCreate(
            [
                "email" => $data["email"],
                "is_registered" => false,
            ],
            [
                "first_name" => $data["billing"]["first_name"],
                "last_name" => $data["billing"]["last_name"],
                "name" =>
                    $data["billing"]["first_name"] .
                    " " .
                    $data["billing"]["last_name"],
                "orders_count" => 0,
                "total_spent" => 0,
            ]
        );

        // Update customer metrics
        $customer->update([
            "orders_count" => $customer->orders_count + 1,
            "last_order_at" => now(),
        ]);

        return $customer;
    }

    private function handleAddress(
        Customer $customer,
        array $addressData,
        AddressType $type
    ): CustomerAddress {
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
}
