<?php

namespace App\Helpers\Filament\Coupon;

use App\Enums\CouponType;
use App\Models\Coupon;
use App\Services\Currency\CurrencyHelper;
use Filament\Tables;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Money\Money;

class CouponTable
{
    public static function make(
        Table $table,
        array $additionalColumn = []
    ): Table {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("code")
                    ->searchable()
                    ->label(__("store.Code")),

                Tables\Columns\TextColumn::make("discount_type")
                    ->badge()
                    ->label(__("dashboard.Discount Type")),

                Tables\Columns\TextColumn::make("discount_amount")
                    ->label(__("store.Discount Amount"))
                    ->sortable()
                    ->formatStateUsing(function ($state, Coupon $record) {
                        if (
                            $record->discount_type ===
                            CouponType::PERCENTAGE->value
                        ) {
                            return $state . "%";
                        }

                        return CurrencyHelper::format(
                            new Money($state, CurrencyHelper::defaultCurrency())
                        );
                    }),
                ...$additionalColumn,
                Tables\Columns\TextColumn::make("used_count")
                    ->sortable()
                    ->label(__("dashboard.Used Count")),

                Tables\Columns\IconColumn::make("is_active")
                    ->boolean()
                    ->label(__("store.Active")),

                Tables\Columns\TextColumn::make("expires_at")
                    ->label(__("dashboard.Expires at"))
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make("created_at")
                    ->label(__("dashboard.Created At"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("discount_type")
                    ->options(CouponType::class)
                    ->label(__("dashboard.Discount Type")),

                Tables\Filters\TernaryFilter::make("is_active"),
                DateRangeFilter::make("expires_at")->label(
                    __(__("dashboard.Expiration date"))
                ),
                DateRangeFilter::make("created_at")->label(
                    __("dashboard.Created At")
                ),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
