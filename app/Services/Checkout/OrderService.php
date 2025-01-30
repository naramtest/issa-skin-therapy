<?php

namespace App\Services\Checkout;

use App\Data\Orders\CreateOrderData;
use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Models\Order;
use App\Services\Currency\CurrencyHelper;
use App\Services\Currency\CurrencyService;
use App\ValueObjects\CartItem;
use InvalidArgumentException;
use Str;

readonly class OrderService
{
    public function __construct(private CurrencyService $currencyService)
    {
    }

    public function createOrder(CreateOrderData $data): Order
    {
        // Validate order data
        if (empty($data->cartItems)) {
            throw new InvalidArgumentException("Cart cannot be empty");
        }

        // Generate unique order number
        $orderNumber = $this->generateOrderNumber();

        // Create the order
        $userCurrency = CurrencyHelper::getUserCurrency();
        $defaultCurrency = CurrencyHelper::defaultCurrency();
        $order = Order::create([
            "order_number" => $orderNumber,
            "customer_id" => $data->customerId,
            "email" => $data->email,
            "billing_address_id" => $data->billingAddressId,
            "shipping_address_id" => $data->shippingAddressId,
            "status" => $data->status,
            "payment_status" => $data->paymentStatus,
            "shipping_method" => $data->shippingMethod,
            "subtotal" => $data->subtotal,
            "shipping_cost" => $data->shippingCost,
            "total" => $data->total,
            "notes" => $data->notes,
            "currency_code" => $userCurrency,
            "default_currency" => $defaultCurrency,
            "exchange_rate" => $this->currencyService->getExchangeRate(
                $defaultCurrency,
                $userCurrency
            ),
        ]);

        // Create order items
        //TODO: can be improved
        foreach ($data->cartItems as $item) {
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
