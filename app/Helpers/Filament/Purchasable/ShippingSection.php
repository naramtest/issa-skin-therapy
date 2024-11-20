<?php

namespace App\Helpers\Filament\Purchasable;

use Filament\Forms;
use Filament\Forms\Components\Tabs;

class ShippingSection
{
    public static function make(array $array)
    {
        return Tabs\Tab::make(__("store.Shipping"))
            ->icon("gmdi-shopping-cart-o")
            ->columns()
            ->schema([
                Forms\Components\TextInput::make("weight")
                    ->label(__("dashboard.Weight"))
                    ->numeric()
                    ->step(0.001)
                    ->suffix("kg"),

                Forms\Components\TextInput::make("length")
                    ->label(__("dashboard.Length"))
                    ->numeric()
                    ->step(0.01)
                    ->suffix("cm"),

                Forms\Components\TextInput::make("width")
                    ->label(__("dashboard.Width"))
                    ->numeric()
                    ->step(0.01)
                    ->suffix("cm"),

                Forms\Components\TextInput::make("height")
                    ->label(__("dashboard.Height"))
                    ->numeric()
                    ->step(0.01)
                    ->suffix("cm"),
                ...$array,
            ]);
    }
}
