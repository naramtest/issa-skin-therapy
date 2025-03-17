<?php

namespace App\Filament\Exports;

use App\Models\Subscriber;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class SubscriberExporter extends Exporter
{
    protected static ?string $model = Subscriber::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make("email")->label("Email"),
            ExportColumn::make("status")
                ->label("Status")
                ->state(function (Subscriber $order): string {
                    return $order->status->getLabel();
                }),
            ExportColumn::make("created_at")
                ->label("Subscribed On")
                ->formatStateUsing(
                    fn($state): string => $state->format("Y-m-d H:i:s")
                ),
            ExportColumn::make("updated_at")
                ->label("Last Updated")
                ->formatStateUsing(
                    fn($state): string => $state->format("Y-m-d H:i:s")
                ),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body =
            "Your subscriber export has completed and " .
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
