<?php

namespace App\Helpers\DHL;

class PaperlessTradeHelper
{
    /**
     * Check if a given country code is in the PLT list.
     *
     * @param string $countryCode
     * @return bool
     */
    public static function isPaperlessTradeCountry(string $countryCode): bool
    {
        $pltCountries = array_keys(config("plt_countries.plt_countries"));

        return in_array(strtoupper($countryCode), $pltCountries);
    }
}
