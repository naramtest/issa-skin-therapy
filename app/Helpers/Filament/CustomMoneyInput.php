<?php

namespace App\Helpers\Filament;

use App\Services\Currency\CurrencyHelper;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;

class CustomMoneyInput
{
    public static function make(string $field, \Closure $money)
    {
        return MoneyInput::make($field)->formatStateUsing(function (
            string $operation,
            $record
        ) use ($money) {
            if ($operation == "edit") {
                if ($money($record)) {
                    return CurrencyHelper::decimalFormatter($money($record));
                }
            }
            return null;
        });
    }
}
