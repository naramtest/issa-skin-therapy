<?php

use App\Services\Currency\Currency;
use Money\Money;

if (!function_exists("userPrice")) {
    function userPrice(Money $money): Money
    {
        return Currency::convertToUserCurrencyWithCache($money);
    }
}
