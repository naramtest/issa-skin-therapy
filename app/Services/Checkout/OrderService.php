<?php

namespace App\Services\Checkout;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Models\Order;
use App\Services\Currency\CurrencyHelper;
use App\ValueObjects\CartItem;
use InvalidArgumentException;
use Str;

class OrderService
{
    public function createOrder(array $data): Order
    {
        // Validate order data
        $this->validateOrderData($data);

        // Generate unique order number
        $orderNumber = $this->generateOrderNumber();

        // Create the order
        $order = Order::create([
            "order_number" => $orderNumber,
            "customer_id" => $data["customer_id"],
            "email" => $data["email"],
            "billing_address_id" => $data["billing_address_id"],
            "shipping_address_id" => $data["shipping_address_id"],
            "status" => $data["status"],
            "payment_status" => $data["payment_status"],
            "shipping_method" => $data["shipping_method"],
            "subtotal" => $data["subtotal"],
            "shipping_cost" => 0, // Will be calculated with DHL integration
            "total" => $data["total"],
            "notes" => $data["notes"],
            "currency_code" => app(CurrencyHelper::class)->getUserCurrency(),
            "exchange_rate" => 1, //TODO: Will be set based on the currency service
        ]);

        // Create order items
        foreach ($data["cart_items"] as $item) {
            $order->items()->create([
                "purchasable_id" => $item->getPurchasable()->getId(),
                "purchasable_type" => get_class($item->getPurchasable()),
                "quantity" => $item->getQuantity(),
                "unit_price" => $item->getPrice()->getAmount(),
                "subtotal" => $item->getSubtotal()->getAmount(),
                "options" => $item->getOptions(),
            ]);

            // Handle inventory
            $this->handleInventory($item);
        }

        // Update customer total spent
        $order->customer->increment("total_spent", $order->total);

        //TODO:  Fire events
        //event(new OrderCreated($order));

        return $order;
    }

    private function validateOrderData(array $data): void
    {
        $requiredFields = [
            "customer_id",
            "email",
            "billing_address_id",
            "shipping_address_id",
            "status",
            "payment_status",
            "subtotal",
            "total",
            "cart_items",
        ];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new InvalidArgumentException(
                    "Missing required field: {$field}"
                );
            }
        }

        if (empty($data["cart_items"])) {
            throw new InvalidArgumentException("Cart cannot be empty");
        }
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = "ORD-" . date("Ymd") . "-" . strtoupper(Str::random(5));
        } while (Order::where("order_number", $number)->exists());

        return $number;
    }

    private function handleInventory(CartItem $item): void
    {
        $purchasable = $item->getPurchasable();

        if ($purchasable->inventory()->shouldTrackQuantity()) {
            // Reduce inventory
            $purchasable->decrement("quantity", $item->getQuantity());

            // Update stock status
            $purchasable->update([
                "stock_status" => $purchasable
                    ->inventory()
                    ->determineStockStatus(),
            ]);
        }
    }

    public function isOrderPendingPayment(Order $order): bool
    {
        return $order->status === OrderStatus::PENDING &&
            $order->payment_status === PaymentStatus::PENDING &&
            $order->payment_intent_id !== null;
    }

    public function getOrderByPaymentIntent(string $paymentIntentId): ?Order
    {
        return Order::where("payment_intent_id", $paymentIntentId)->first();
    }
}
