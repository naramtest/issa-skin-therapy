<?php

namespace App\Filament\Exports;

use App\Models\Customer;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CustomerExporter extends Exporter
{
    protected static ?string $model = Customer::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make("id")->label("ID"),

            ExportColumn::make("first_name")->label("First Name"),

            ExportColumn::make("last_name")->label("Last Name"),

            ExportColumn::make("email")->label("Email"),

            ExportColumn::make("is_registered")
                ->label("Registered Customer")
                ->state(function (Customer $customer): string {
                    return $customer->is_registered ? "Yes" : "No";
                }),

            ExportColumn::make("orders_count")->label("Orders Count"),

            ExportColumn::make("total_spent")->label("Total Spent"),

            ExportColumn::make("last_order_at")
                ->label("Last Order Date")
                ->formatStateUsing(
                    fn($state) => $state ? $state->format("Y-m-d H:i:s") : ""
                ),

            ExportColumn::make("created_at")
                ->label("Registration Date")
                ->formatStateUsing(fn($state) => $state->format("Y-m-d H:i:s")),

            ExportColumn::make("defaultAddress.full_address")->label(
                "Default Address"
            ),

            ExportColumn::make("defaultAddress.phone")->label("Phone Number"),

            ExportColumn::make("defaultAddress.country")->label("Country"),

            ExportColumn::make("defaultAddress.city")->label("City"),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body =
            "Your customer export has completed and " .
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
