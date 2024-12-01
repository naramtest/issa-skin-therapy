<?php

namespace App\Models;

use App\Services\Info\InfoCacheService;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Info extends Model
{
    use HasTranslations;

    public array $translatable = ["name", "about", "address", "slogan"];
    protected $guarded = [];
    protected $casts = [
        "phone" => "array",
        "email" => "array",
        "social" => "array",
    ];

    protected static function booted(): void
    {
        static::created(function () {
            $infoCacheService = app(InfoCacheService::class);
            $infoCacheService->clearInfoCache();
        });
        static::updated(function () {
            $infoCacheService = app(InfoCacheService::class);
            $infoCacheService->clearInfoCache();
        });
    }
}
