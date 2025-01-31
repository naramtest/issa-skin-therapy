<?php

namespace App\Filament\Resources\OrderResource\Partials\Components;

use App\Enums\Checkout\DHLProduct;
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
                    ->disabled()
                    ->columnSpan(3),
                MoneyInput::make("total")
                    ->label(__("store.Total"))
                    ->disabled()
                    ->columnSpan(3),

                TextInput::make("shipping_method")
                    ->label(__("store.Shipping Method"))
                    ->formatStateUsing(function ($state) {
                        if (
                            in_array(
                                $state,
                                array_column(DHLProduct::cases(), "value")
                            )
                        ) {
                            $dhlProduct = DHLProduct::tryFrom($state);
                            if ($dhlProduct !== null) {
                                return $dhlProduct->getLabel();
                            }
                            return $state;
                        }
                    })
                    ->maxLength(255)
                    ->columnSpan(2),

                Select::make("payment_status")
                    ->options(PaymentStatus::class)
                    ->label(__("store.Payment Status"))
                    ->columnSpan(2),
                Select::make("status")
                    ->options(OrderStatus::class)
                    ->label(__("store.Order Status"))
                    ->columnSpan(2),
                Textarea::make("note")
                    ->rows(4)
                    ->label(__("store.Note"))
                    ->columnSpan(6),
            ])
            ->columns(6);
    }
}
