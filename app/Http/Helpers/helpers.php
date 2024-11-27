<?php

use Carbon\Carbon;

if (!function_exists("formattedDate")) {
    function formattedDate(Carbon $date): string
    {
        return $date->translatedFormat("F j, Y");
    }
}
