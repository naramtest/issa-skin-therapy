<?php

namespace App\Filament\Exports;

use App\Models\ContactMessage;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ContactMessageExporter extends Exporter
{
    protected static ?string $model = ContactMessage::class;

    /**
     * Define a default sort order for the export
     */
    public static function getDefaultSort(): ?array
    {
        return ["created_at" => "desc"];
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make("name")->label("Name"),

            ExportColumn::make("email")->label("Email"),

            ExportColumn::make("phone")->label("Phone"),

            ExportColumn::make("subject")->label("Subject"),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body =
            "Your contact messages export has completed and " .
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
