<?php

namespace App\Filament\Resources\OrderResource\Partials\Components;

use App\Helpers\Filament\Component\DateTextInput;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;

class PaymentTab
{
    public static function make()
    {
        return Tab::make(__("store.Payment"))
            ->schema([
                //TODO: make it copyable
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make("payment_provider")
                            ->label(__("store.Payment Provider"))
                            ->disabled(),
                        Forms\Components\TextInput::make("payment_intent_id")
                            ->label(__("store.Payment Intent Id"))
                            ->disabled(),
                    ])
                    ->columns()
                    ->columnSpan(3),
                DateTextInput::make("payment_authorized_at")
                    ->label(__("store.Payment Authorized At"))
                    ->disabled(),
                DateTextInput::make("payment_captured_at")
                    ->label(__("store.Payment Captured At"))
                    ->disabled(),
                DateTextInput::make("payment_refunded_at")
                    ->label(__("store.Refunded At"))
                    ->disabled(),
            ])
            ->columns(3);
    }
}
