<?php

use Carbon\Carbon;

if (!function_exists("formattedDate")) {
    function formattedDate(Carbon $date): string
    {
        return $date->translatedFormat("F j, Y");
    }
}

if (!function_exists("getPageTitle")) {
    function getPageTitle($title): string
    {
        $brand = getLocalAppName();
        return $title . " - " . $brand;
    }
}

if (!function_exists("getLocalAppName")) {
    function getLocalAppName(): string
    {
        return config("app.name_" . app()->getLocale());
    }
}
