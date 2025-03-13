<?php

namespace App\Filament\Exports;

use App\Models\Order;
use App\Services\Currency\CurrencyHelper;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class OrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make("id")->label("ID"),

            ExportColumn::make("order_number")->label("Order Number"),

            ExportColumn::make("customer.full_name")->label("Customer Name"),

            ExportColumn::make("email")->label("Customer Email"),

            ExportColumn::make("status")
                ->label("Order Status")
                ->state(function (Order $order): string {
                    return $order->status->getLabel();
                }),

            ExportColumn::make("payment_status")
                ->label("Payment Status")
                ->state(function (Order $order): string {
                    return $order->payment_status->getLabel();
                }),

            ExportColumn::make("payment_provider")->label("Payment Provider"),

            ExportColumn::make("subtotal")
                ->label("Subtotal")
                ->formatStateUsing(function (Order $order): string {
                    return CurrencyHelper::format($order->getMoneySubtotal());
                }),

            ExportColumn::make("shipping_cost")
                ->label("Shipping Cost")
                ->formatStateUsing(function (Order $order): string {
                    return CurrencyHelper::format($order->money_shipping_cost);
                }),

            ExportColumn::make("total")
                ->label("Total")
                ->formatStateUsing(function (Order $order): string {
                    return CurrencyHelper::format($order->getMoneyTotal());
                }),

            ExportColumn::make("currency_code")->label("Currency"),

            ExportColumn::make("shipping_method")
                ->label("Shipping Method")
                ->state(function (Order $order): string {
                    return $order->shipping_method->getLabel();
                }),

            ExportColumn::make("shippingAddress.country")->label(
                "Shipping Country"
            ),

            ExportColumn::make("shippingAddress.city")->label("Shipping City"),

            ExportColumn::make("shippingAddress.postal_code")->label(
                "Shipping Postal Code"
            ),

            ExportColumn::make("shippingAddress.full_address")->label(
                "Shipping Address"
            ),

            ExportColumn::make("shippingAddress.phone")->label(
                "Shipping Phone"
            ),

            ExportColumn::make("items_count")
                ->label("Items Count")
                ->state(function (Order $order): int {
                    return $order->items->sum("quantity");
                }),

            ExportColumn::make("created_at")
                ->label("Order Date")
                ->formatStateUsing(fn($state) => $state->format("Y-m-d H:i:s")),

            ExportColumn::make("payment_captured_at")
                ->label("Payment Captured Date")
                ->formatStateUsing(
                    fn($state) => $state ? $state->format("Y-m-d H:i:s") : ""
                ),

            ExportColumn::make("payment_refunded_at")
                ->label("Payment Refunded Date")
                ->formatStateUsing(
                    fn($state) => $state ? $state->format("Y-m-d H:i:s") : ""
                ),

            ExportColumn::make("notes")->label("Order Notes"),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body =
            "Your orders export has completed and " .
            number_format($export->successful_rows) .
            " " .
            str("row")->plural($export->successful_rows) .
            " exported.";

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .=
                " " .
                number_format($failedRowsCount) .
                " " .
                str("row")->plural($failedRowsCount) .
                " failed to export.";
        }

        return $body;
    }
}
