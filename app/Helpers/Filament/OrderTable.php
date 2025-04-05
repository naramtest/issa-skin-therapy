<?php

namespace App\Helpers\Filament;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use Filament\Tables;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;

class OrderTable
{
    public static function make(Table $table, array $columns = [])
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("order_number")
                    ->label(__("store.Order Number"))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("customer.full_name")
                    ->label(__("store.Customer"))
                    ->searchable(["first_name", "last_name"]),
                Tables\Columns\TextColumn::make("customer.email")->label(
                    __("store.Email")
                ),
                ...$columns,
                MoneyColumn::make("total")
                    ->sortable()
                    ->label(__("store.Total")),

                Tables\Columns\TextColumn::make("status")
                    ->label(__("dashboard.Status"))
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make("payment_status")
                    ->label(__("store.Payment Status"))
                    ->badge(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label(__("dashboard.Created At"))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("status")->options(
                    OrderStatus::class
                ),
                Tables\Filters\SelectFilter::make("payment_status")->options(
                    PaymentStatus::class
                ),
                DateRangeFilter::make("created_at")->label(
                    __("dashboard.Created At")
                ),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->defaultSort("created_at", "desc");
    }
}
