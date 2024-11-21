<?php

namespace App\Helpers\Filament\Purchasable;

use App\Enums\StockStatus;
use Exception;
use Filament\Tables;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;

class PurchasableTable
{
    public static function columns(): array
    {
        return [
            Tables\Columns\TextColumn::make("name")
                ->label(__("store.Name"))
                ->searchable()
                ->grow(false)
                ->sortable(),

            MoneyColumn::make("regular_price")
                ->label(__("dashboard.Regular Price"))
                ->sortable(),
            MoneyColumn::make("sale_price")
                ->label(__("dashboard.Sale Price"))
                ->sortable()
                ->toggleable(true, true),
            Tables\Columns\TextColumn::make("stock_status")
                ->badge()
                ->sortable()
                ->label(__("dashboard.Stock Status"))
                ->toggleable(),

            Tables\Columns\TextColumn::make("created_at")
                ->label(__("dashboard.Created At"))
                ->dateTime("M j, Y")
                ->sortable(),
        ];
    }

    /**
     * @throws Exception
     */
    public static function filters(): array
    {
        return [
            Tables\Filters\SelectFilter::make("stock_status")
                ->options(StockStatus::class)
                ->label(__("dashboard.Stock Status")),
            Tables\Filters\TernaryFilter::make("is_sale_scheduled")->label(
                __("dashboard.On Sale")
            ),
            Tables\Filters\TrashedFilter::make(),
        ];
    }

    public static function actions(): array
    {
        return [
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
            Tables\Actions\ForceDeleteAction::make(),
            Tables\Actions\RestoreAction::make(),
        ];
    }

    public static function bulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]),
        ];
    }
}
