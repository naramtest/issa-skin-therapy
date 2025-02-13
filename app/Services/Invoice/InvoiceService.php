<?php

namespace App\Services\Invoice;

use App\Models\Info;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Currency\Currency;
use App\Services\Currency\CurrencyHelper;
use Finller\Invoice\Invoice;
use Finller\Invoice\InvoiceItem;
use Finller\Invoice\InvoiceState;
use Finller\Invoice\InvoiceType;
use Illuminate\Support\Collection;
use Money\Money;

class InvoiceService
{
    public function generateInvoice(Order $order): Invoice
    {
        // Create new invoice
        $invoice = new Invoice([
            "type" => InvoiceType::Invoice,
            "state" => InvoiceState::Paid,
            "description" => "$order->order_number",
            "seller_information" => $this->getSellerInfo(),
            "buyer_information" => $this->getBuyerInfo($order),
            "currency" => $order->currency_code,
        ]);

        // Optionally associate with customer if they are registered
        if ($order->customer->user) {
            $invoice->buyer()->associate($order->customer);
        }

        // Associate with order
        $invoice->invoiceable()->associate($order);

        // Save invoice to get serial number
        $invoice->save();
        //        $items = $this->invoiceItems($order);
        //
        //        $invoice->items()->saveMany($items->all());
        return $invoice;
    }

    /**
     * @return array
     */
    public function getSellerInfo(): array
    {
        $info = Info::first();

        return [
            "name" => $info->name,
            "address" => [
                "country" => "United Arab Emirates",
            ],
            "email" => count($info->email) ? $info->email[0]["email"] : null,
            "phone_number" => count($info->phone)
                ? $info->phone[0]["number"]
                : null,
        ];
    }

    /**
     * @param Order $order
     * @return array
     */
    public function getBuyerInfo(Order $order): array
    {
        return [
            "name" => $order->billingAddress->full_name,
            "address" => [
                "street" => $order->billingAddress->address,
                "city" => $order->billingAddress->city,
                "postal_code" => $order->billingAddress->postal_code,
                "state" => $order->billingAddress->state,
                "country" => $order->billingAddress->country,
            ],
            "email" => $order->email,
            "phone_number" => $order->billingAddress->phone,
        ];
    }

    /**
     * @param Order $order
     */
    public function invoiceItems(Order $order): Collection
    {
        // Add items
        $items = $order->items->map(function (OrderItem $item) use ($order) {
            return new InvoiceItem([
                "unit_price" => $this->getAmount(
                    $item->money_unit_price,
                    $order
                ),
                "currency" => $order->currency_code,
                "quantity" => $item->quantity,
                "label" => $item->purchasable->name,
            ]);
        });

        // Add shipping as an item if there's a shipping cost
        if ($order->shipping_cost > 0) {
            $items->push(
                new InvoiceItem([
                    "unit_price" => $this->getAmount(
                        $order->money_shipping_cost,
                        $order
                    ),
                    "currency" => $order->currency_code,
                    "quantity" => 1,
                    "label" => __("store.Shipping"),
                ])
            );
        }
        return $items;
    }

    /**
     * @param Money $price
     * @param Order $order
     * @return string
     */
    function getAmount(Money $price, Order $order): string
    {
        return CurrencyHelper::decimalFormatter(
            Currency::convertToUserCurrencyWithCache(
                $price,
                $order->currency_code,
                $order->exchange_rate
            )
        );
    }
}
