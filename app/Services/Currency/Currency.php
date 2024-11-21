<?php

namespace App\Services\Currency;

use Illuminate\Support\Facades\Facade;

class Currency extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CurrencyService::class;
    }
}
