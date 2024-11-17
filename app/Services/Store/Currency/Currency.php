<?php

namespace App\Services\Store\Currency;

use Illuminate\Support\Facades\Facade;

class Currency extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return "currency";
    }
}
