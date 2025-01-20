<?php

namespace App\Filament\Resources\OrderResource\Partials\Components;

use App\Models\CustomerAddress;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class AddressFieldset
{
    public static function make(string $relation, string $label)
    {
        return Fieldset::make($label)
            ->relationship($relation)
            ->schema([
                TextInput::make("name")
                    ->disabled()
                    ->label(__("store.Name"))
                    ->formatStateUsing(
                        fn(
                            CustomerAddress $record
                        ): string => $record->full_name
                    ),
                PhoneInput::make("phone")->disabled()->label(__("store.phone")),

                TextInput::make("postal_code")
                    ->disabled()
                    ->label(__("store.Postal code")),
                TextInput::make("country")
                    ->disabled()
                    ->label(__("store.Country")),
                TextInput::make("state")->disabled()->label(__("store.state")),
                TextInput::make("city")->disabled()->label(__("store.city")),
                TextInput::make("address")
                    ->disabled()
                    ->label(__("dashboard.info.address")),
                Placeholder::make("Address")
                    ->label(__(__("store.Extra")))
                    ->content(function (CustomerAddress $record) {
                        "Area: " .
                            $record->area .
                            ", Building: " .
                            $record->building .
                            ", Flat: " .
                            $record->flat;
                    }),
            ])
            ->columns(3);
    }
}
