<?php

namespace App\Services\SEO;

use Illuminate\Support\Facades\Facade;

class Schema extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return SchemaManager::class;
    }
}
