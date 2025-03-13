<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Models\Order;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = "orders";

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute("order_number")
            ->columns([
                Tables\Columns\TextColumn::make("order_number")
                    ->searchable()
                    ->sortable(),
                MoneyColumn::make("total")->sortable(),
                Tables\Columns\TextColumn::make("status")->badge()->sortable(),
                Tables\Columns\TextColumn::make("payment_status")->badge(),
                Tables\Columns\TextColumn::make("created_at")
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
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->url(
                    fn(Order $record): string => route(
                        "filament.admin.resources.orders.edit",
                        ["record" => $record]
                    )
                ),
            ])
            ->defaultSort("created_at", "desc");
    }
}
