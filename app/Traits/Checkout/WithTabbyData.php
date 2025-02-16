<?php

namespace App\Traits\Checkout;

use App\Models\Order;
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
            "amount" => CurrencyHelper::decimalFormatter($this->total),
            "currency" => CurrencyHelper::getUserCurrency(),
            "description" => "Order #" . time(),
            "buyer" => [
                "phone" => App::isLocal()
                    ? "otp.success@tabby.ai"
                    : $this->form->phone,
                "email" => App::isLocal()
                    ? "+971500000001"
                    : $this->form->email,
                "name" =>
                    $this->form->billing_first_name .
                    " " .
                    $this->form->billing_last_name,
            ],
            "shipping_address" => [
                "city" => $this->form->different_shipping_address
                    ? $this->form->shipping_city
                    : $this->form->billing_city,
                "address" => $this->form->different_shipping_address
                    ? $this->form->shipping_address
                    : $this->form->billing_address,
                "zip" => $this->form->different_shipping_address
                    ? $this->form->shipping_postal_code
                    : $this->form->billing_postal_code,
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
                                $item->getPrice()
                            ),
                            "reference_id" => (string) $item
                                ->getPurchasable()
                                ->getId(),
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
                    ? CurrencyHelper::decimalFormatter($this->discount)
                    : "0.00",
            ],
            "buyer_history" => [
                "registered_since" => auth()->check()
                    ? auth()->user()->created_at->toIso8601String()
                    : now()->toIso8601String(),
                "loyalty_level" => 0,
                "wishlist_count" => 0,
                "is_social_networks_connected" => false,
                "is_phone_number_verified" => false,
                "is_email_verified" => auth()->check(),
            ],
        ];
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
                    ? "otp.success@tabby.ai"
                    : $order->shippingAddress->phone,
                "email" => App::isLocal() ? "+971500000001" : $order->email,
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
            "buyer_history" => $this->getBuyerHistory(),
        ];
    }

    protected function getBuyerHistory()
    {
        return [
            "registered_since" => auth()->check()
                ? auth()->user()->created_at->toIso8601String()
                : now()->toIso8601String(),
            "loyalty_level" => 0,
            "wishlist_count" => 0,
            "is_social_networks_connected" => false,
            "is_phone_number_verified" => false,
            "is_email_verified" => auth()->check(),
        ];
    }
}
