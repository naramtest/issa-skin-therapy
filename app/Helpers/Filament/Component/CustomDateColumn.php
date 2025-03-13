<?php

namespace App\Helpers\Filament\Component;

use Filament\Tables\Columns\TextColumn;

class CustomDateColumn
{
    public static function make($field, $format = "M j, Y")
    {
        return TextColumn::make($field)
            ->dateTime($format)
            ->sortable()
            ->toggleable();
    }
}
