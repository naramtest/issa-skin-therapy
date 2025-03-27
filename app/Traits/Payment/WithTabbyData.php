<?php

namespace App\Traits\Payment;

use App\Enums\Checkout\PaymentStatus;
use App\Models\Customer;
use App\Models\Order;
use App\Services\Currency\Currency;
use App\Services\Currency\CurrencyHelper;
use Illuminate\Support\Facades\App;
use Money\Money;

trait WithTabbyData
{
    protected function getTabbyCheckoutData(?Order $source = null): array
    {
        if (!$source) {
            return $this->getDataFromForm();
        }
        return $this->getDataFromOrder($source);
    }

    protected function getDataFromForm(): array
    {
        return [
            "amount" => CurrencyHelper::decimalFormatter(
                $this->convertMoney($this->total)
            ),
            "currency" => CurrencyHelper::getUserCurrency(),
            "description" => "Order #" . time(),
            "buyer" => [
                "email" => App::isLocal()
                    ? "otp.rejected@tabby.ai"
                    : $this->form->email,
                "phone" => App::isLocal()
                    ? "+966500000001"
                    : $this->form->phone,
                "name" =>
                    $this->form->billing_first_name .
                    " " .
                    $this->form->billing_last_name,
            ],
            "order" => [
                "reference_id" => (string) time(),
                "items" => collect($this->cartItems)
                    ->map(function ($item) {
                        return [
                            "discount_amount" => "0.00", //TODO: add item discount amount
                            "title" => $item->getPurchasable()->getName(),
                            "quantity" => $item->getQuantity(),
                            "unit_price" => CurrencyHelper::decimalFormatter(
                                $this->convertMoney($item->getPrice())
                            ),
                            "reference_id" => (string) $item
                                ->getPurchasable()
                                ->getId(),
                            "category" => $this->getItemCategory(
                                $item->getPurchasable()
                            ),
                        ];
                    })
                    ->values()
                    ->toArray(),
                "tax_amount" => "0.00",
                "shipping_amount" => CurrencyHelper::decimalFormatter(
                    new Money(
                        $this->shippingCost,
                        CurrencyHelper::userCurrency()
                    )
                ),
                "discount_amount" => $this->discount
                    ? CurrencyHelper::decimalFormatter(
                        $this->convertMoney($this->discount)
                    )
                    : "0.00",
            ],
            "buyer_history" => [
                "registered_since" => auth()->check()
                    ? auth()->user()->created_at->toIso8601String()
                    : now()->toIso8601String(),
                "loyalty_level" => $this->getCustomerLoyaltyLevel(
                    email: $this->form->email
                ),
                "wishlist_count" => 0,
                "is_social_networks_connected" => false,
                "is_phone_number_verified" => false,
                "is_email_verified" => auth()->check(),
            ],
            "order_history" => $this->getOrderHistory(
                email: $this->form->email
            ),
        ];
    }

    public function convertMoney(
        Money $money,
        ?string $currency_code = null
    ): Money {
        return Currency::convertToUserCurrencyWithCache($money, $currency_code);
    }

    protected function getItemCategory($purchasable): string
    {
        // Try to get categories from the purchasable if it has them
        if (
            method_exists($purchasable, "categories") &&
            $purchasable->categories()->exists()
        ) {
            $categories = $purchasable->categories;

            if ($categories->isNotEmpty()) {
                // Return a tree of category-subcategory if possible
                $categoryNames = $categories->pluck("name")->toArray();
                return implode("-", $categoryNames);
            }
        }

        // Default category if no categories are found
        return "General";
    }

    protected function getCustomerLoyaltyLevel(
        $customerId = null,
        ?string $email = null
    ): int {
        if (!$customerId) {
            if (auth()->check() && auth()->user()->customer) {
                $customerId = auth()->user()->customer->id;
            } else {
                $customerId = Customer::where("email", $email)
                    ->where("is_registered", false)
                    ->first()?->id;
            }
        }

        // If we have a customer ID, count their successful orders
        if ($customerId) {
            return Order::where("customer_id", $customerId)
                ->where("payment_status", PaymentStatus::PAID)
                ->count();
        }

        return 0;
    }

    protected function getOrderHistory(
        $customerId = null,
        ?string $email = null
    ): array {
        // If no customer ID was provided but user is logged in, use the authenticated user's customer ID
        if (!$customerId) {
            if (auth()->check() && auth()->user()->customer) {
                $customerId = auth()->user()->customer->id;
            } else {
                $customerId = Customer::where("email", $email)
                    ->where("is_registered", false)
                    ->first()?->id;
            }
        }

        // If we don't have a customer ID, return empty order history
        if (!$customerId) {
            return [];
        }

        // Get the last 5 paid orders for this customer
        $orders = Order::where("customer_id", $customerId)
            ->where("payment_status", PaymentStatus::PAID)
            ->orderBy("created_at", "desc")
            ->limit(5)
            ->get();

        return $orders
            ->map(function (Order $order) {
                return [
                    "purchased_at" => $order->created_at->toIso8601String(),
                    "amount" => CurrencyHelper::decimalFormatter(
                        $this->convertMoney(
                            $order->getMoneyTotal(),
                            $order->currency_code
                        )
                    ),
                    "status" => $order->status->value,
                    "buyer" => [
                        "phone" => $order->shippingAddress->phone ?? "",
                        "email" => $order->email ?? "",
                        "name" => $order->shippingAddress->full_name ?? "",
                    ],
                    "shipping_address" => [
                        "city" => $order->shippingAddress->city ?? "",
                        "address" => $order->shippingAddress->address ?? "",
                        "zip" => $order->shippingAddress->postal_code ?? "",
                    ],
                ];
            })
            ->toArray();
    }

    protected function getDataFromOrder(Order $order): array
    {
        return [
            "amount" => CurrencyHelper::decimalFormatter(
                $order->getMoneyTotal()
            ),
            "currency" => $order->currency_code,
            "description" => "Order #{$order->order_number}",
            "buyer" => [
                "phone" => App::isLocal()
                    ? "+966500000001"
                    : $order->shippingAddress->phone,
                "email" => App::isLocal()
                    ? "otp.success@tabby.ai"
                    : $order->email,
                "name" => $order->shippingAddress->full_name,
            ],
            "shipping_address" => [
                "city" => $order->shippingAddress->city,
                "address" => $order->shippingAddress->address,
                "zip" => $order->shippingAddress->postal_code,
            ],
            "order" => [
                "reference_id" => $order->order_number,
                "items" => $order->items
                    ->map(
                        fn($item) => [
                            "discount_amount" => "0.00",
                            "title" => $item->purchasable->name,
                            "quantity" => $item->quantity,
                            "unit_price" => CurrencyHelper::decimalFormatter(
                                $item->money_unit_price
                            ),
                            "reference_id" => (string) $item->purchasable_id,
                            "category" => $this->getItemCategory(
                                $item->purchasable
                            ),
                        ]
                    )
                    ->values(),
                "tax_amount" => "0.00",
                "shipping_amount" => CurrencyHelper::decimalFormatter(
                    $order->money_shipping_cost
                ),
                "discount_amount" => $order->couponUsage
                    ? $order->couponUsage->discount_amount
                    : "0.00",
            ],
            "buyer_history" => $this->getBuyerHistory($order->customer_id),
            "order_history" => $this->getOrderHistory($order->customer_id),
        ];
    }

    protected function getBuyerHistory($customerId = null): array
    {
        return [
            "registered_since" => auth()->check()
                ? auth()->user()->created_at->toIso8601String()
                : now()->toIso8601String(),
            "loyalty_level" => $this->getCustomerLoyaltyLevel($customerId),
            "wishlist_count" => 0,
            "is_social_networks_connected" => false,
            "is_phone_number_verified" => false,
            "is_email_verified" => auth()->check(),
        ];
    }

    protected function hasTypeCompleteAddress(): bool
    {
        return !empty($this->form->phone) and
            !empty($this->form->email) and
            !empty($this->form->billing_first_name) and
            !empty($this->form->billing_last_name);
    }
}
