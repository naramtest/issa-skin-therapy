<?php

namespace App\Services\Checkout;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\Currency\CurrencyHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

readonly class OrderService
{
    public function __construct(private CartService $cartService)
    {
    }

    public function createOrder(
        Customer $customer,
        CustomerAddress $billingAddress,
        CustomerAddress $shippingAddress,
        ?string $notes = null,
        ?string $shippingMethod = null
    ): Order {
        return DB::transaction(function () use (
            $customer,
            $billingAddress,
            $shippingAddress,
            $notes,
            $shippingMethod
        ) {
            // Create the order
            $order = Order::create([
                "order_number" => $this->generateOrderNumber(),
                "customer_id" => $customer->id,
                "billing_address_id" => $billingAddress->id,
                "shipping_address_id" => $shippingAddress->id,
                "status" => OrderStatus::PENDING,
                "payment_status" => PaymentStatus::PENDING,
                "shipping_method" => $shippingMethod,
                "subtotal" =>
                    $this->cartService->getSubtotal()->getAmount() / 100,
                "shipping_cost" => 0, // TODO: Will be calculated later with DHL integration
                "total" => $this->cartService->getTotal()->getAmount() / 100,
                "notes" => $notes,
                "currency_code" => CurrencyHelper::getUserCurrency(),
                "exchange_rate" => 1, // TODO: Will be set based on the currency service
            ]);

            // Create order items
            foreach ($this->cartService->getItems() as $cartItem) {
                $order->items()->create([
                    "purchasable_id" => $cartItem->getPurchasable()->getId(),
                    "purchasable_type" => get_class(
                        $cartItem->getPurchasable()
                    ),
                    "quantity" => $cartItem->getQuantity(),
                    "unit_price" => $cartItem->getPrice()->getAmount() / 100,
                    "subtotal" => $cartItem->getSubtotal()->getAmount() / 100,
                    "options" => $cartItem->getOptions(),
                ]);
            }

            // Clear the cart
            $this->cartService->clear();

            // Update customer metrics
            $customer->update([
                "orders_count" => $customer->orders_count + 1,
                "total_spent" => $customer->total_spent + $order->total,
                "last_order_at" => Carbon::now(),
            ]);

            return $order;
        });
    }

    public function generateOrderNumber(): string
    {
        $prefix = config("shop.order_prefix", "ORD");
        $timestamp = now()->format("Ymd");

        do {
            $random = strtoupper(Str::random(4));
            $orderNumber = "{$prefix}-{$timestamp}-{$random}";
        } while (Order::where("order_number", $orderNumber)->exists());

        return $orderNumber;
    }

    public function updateOrderStatus(Order $order, OrderStatus $status): void
    {
        $order->update(["status" => $status]);
        // TODO: Send notification about status change
    }

    public function updatePaymentStatus(
        Order $order,
        PaymentStatus $status
    ): void {
        $order->update(["payment_status" => $status]);
        // TODO: Send notification about payment status change
    }
}
