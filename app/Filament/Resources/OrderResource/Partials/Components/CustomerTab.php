<?php

namespace App\Filament\Resources\OrderResource\Partials\Components;

use App\Helpers\Filament\Component\DateTextInput;
use App\Models\Customer;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;

class CustomerTab
{
    public static function make()
    {
        return Tabs\Tab::make(__("store.Customer"))->schema([
            Group::make()
                ->columns(3)
                ->relationship("customer")
                ->schema([
                    TextInput::make("name")
                        ->disabled()
                        ->label(__("store.Name"))
                        ->helperText(
                            __(
                                "store.Address name may differ from customer name"
                            )
                        )
                        ->formatStateUsing(
                            fn(Customer $record): string => $record->full_name
                        ),
                    TextInput::make("email")
                        ->label(__("store.Email"))
                        ->disabled()
                        ->columnSpan(1),
                    TextInput::make("orders_count")
                        ->label(__("store.Orders Count"))
                        ->disabled()
                        ->columnSpan(1),
                    MoneyInput::make("total_spent")
                        ->label(__("store.Total Spent"))
                        ->disabled(),
                    DateTextInput::make("last_order_at")
                        ->label(__("store.Last Order At"))
                        ->disabled()
                        ->columnSpan(1),
                ]),
        ]);
    }
}
