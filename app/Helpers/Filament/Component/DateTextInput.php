<?php

namespace App\Helpers\Filament\Component;

use Carbon\Carbon;
use Filament\Forms\Components\TextInput;

class DateTextInput
{
    public static function make(string $field)
    {
        return TextInput::make($field)->formatStateUsing(
            fn($state) => $state
                ? Carbon::parse($state)->format("M j, Y - H:i")
                : null
        );
    }
}
