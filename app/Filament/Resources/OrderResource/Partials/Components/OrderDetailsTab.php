<?php

namespace App\Filament\Resources\OrderResource\Partials\Components;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;

class OrderDetailsTab
{
    public static function make()
    {
        return Tabs\Tab::make(__("store.Order Details"))
            ->schema([
                TextInput::make("order_number")
                    ->prefix("#")
                    ->label(__("store.Order Number"))
                    ->disabled(),
                MoneyInput::make("total")->label(__("store.Total"))->disabled(),
                TextInput::make("payment_method")
                    ->label(__("store.Payment Method"))
                    ->maxLength(255),
                TextInput::make("shipping_method")
                    ->label(__("store.Shipping Method"))
                    ->maxLength(255),

                Select::make("payment_status")
                    ->options(PaymentStatus::class)
                    ->label(__("store.Payment Status")),
                Select::make("status")
                    ->options(OrderStatus::class)
                    ->label(__("store.Order Status")),
                Textarea::make("note")->label(__("store.Note"))->columnSpan(3),
            ])
            ->columns(3);
    }
}
