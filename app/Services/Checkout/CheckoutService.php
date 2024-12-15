<?php

namespace App\Services\Checkout;

use App\Models\Customer;
use App\Models\CustomerAddress;
use DB;

class CheckoutService
{
    public function handleGuestCheckout(array $orderData)
    {
        return DB::transaction(function () use ($orderData) {
            // Find or create customer by email
            $customer = Customer::firstOrCreate(
                ["email" => $orderData["email"]],
                [
                    "name" => $orderData["name"],
                    "orders_count" => 0,
                    "total_spent" => 0,
                ]
            );

            // Update customer metrics
            $customer->update([
                "name" => $orderData["name"], // Update name in case it changed
                "orders_count" => $customer->orders_count + 1,
                "last_order_at" => now(),
                "total_spent" => $customer->total_spent + $orderData["total"],
            ]);

            // Find or create address
            $address = $this->findOrCreateAddress($customer, $orderData);

            //            // Create order
            //            $order = Order::create([
            //                'order_number' => $this->generateOrderNumber(),
            //                'customer_id' => $customer->id,
            //                'customer_address_id' => $address->id,
            //                'total' => $orderData['total'],
            //                'subtotal' => $orderData['subtotal'],
            //                'tax' => $orderData['tax'],
            //                'shipping_fee' => $orderData['shipping_fee'] ?? 0,
            //                'discount' => $orderData['discount'] ?? 0,
            //                'status' => 'pending',
            //                'payment_status' => 'pending',
            //                'notes' => $orderData['notes'] ?? null,
            //                'shipping_method' => $orderData['shipping_method'],
            //                'shipping_name' => $address->name,
            //                'shipping_phone' => $address->phone,
            //                'shipping_address' => $address->address,
            //                'shipping_city' => $address->city,
            //                'shipping_country' => $address->country,
            //                'shipping_postal_code' => $address->postal_code,
            //            ]);
            //
            //            // Create order items from cart
            //            if (isset($orderData['cart_id'])) {
            //                $cart = Cart::findOrFail($orderData['cart_id']);
            //                foreach ($cart->items as $item) {
            //                    $order->items()->create([
            //                        'product_id' => $item->product_id,
            //                        'quantity' => $item->quantity,
            //                        'price' => $item->price,
            //                        'total' => $item->total,
            //                        'options' => $item->options,
            //                    ]);
            //                }
            //                // Clear the cart after order creation
            //                $cart->delete();
            //            }

            // Send emails
            //            $this->sendOrderConfirmation($order);

            // If customer is not registered, send registration invitation
            if (!$customer->is_registered) {
                //                $this->sendRegistrationInvitation($customer);
            }

            //            return $order;
        });
    }

    protected function findOrCreateAddress(
        Customer $customer,
        array $data
    ): CustomerAddress {
        // Try to find a matching address
        $existingAddress = $customer
            ->addresses()
            ->where("address", $data["address"])
            ->where("postal_code", $data["postal_code"] ?? "")
            ->first();

        if ($existingAddress) {
            // Update the existing address with new contact details if provided
            $existingAddress->update([
                "name" => $data["name"],
                "phone" => $data["phone"] ?? $existingAddress->phone,
                "last_used_at" => now(),
            ]);

            return $existingAddress;
        }

        // Create new address if none exists
        return $this->createAddress(
            $customer,
            $data,
            !$customer->addresses()->exists()
        );
    }

    protected function createAddress(
        Customer $customer,
        array $data,
        bool $isDefault = false
    ): CustomerAddress {
        return CustomerAddress::create([
            "customer_id" => $customer->id,
            "name" => $data["name"],
            "phone" => $data["phone"] ?? null,
            "address" => $data["address"],
            "city" => $data["city"] ?? null,
            "country" => $data["country"] ?? null,
            "postal_code" => $data["postal_code"] ?? null,
            "is_default" => $isDefault,
            "last_used_at" => now(),
        ]);
    }

    //    protected function generateOrderNumber(): string
    //    {
    //        $prefix = 'ORD';
    //        $date = now()->format('Ymd');
    //        $random = strtoupper(Str::random(4));
    //        return "{$prefix}-{$date}-{$random}";
    //    }
    //
    //    protected function sendOrderConfirmation(Order $order): void
    //    {
    //        Mail::to($order->customer->email)
    //            ->send(new OrderConfirmation($order));
    //    }

    //    TODO: send register email

    //    protected function sendRegistrationInvitation(Customer $customer): void
    //    {
    //        Mail::to($customer->email)
    //            ->send(new RegistrationInvitation($customer));
    //    }
}
