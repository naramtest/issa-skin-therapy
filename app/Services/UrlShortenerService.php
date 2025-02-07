<?php

namespace App\Services;

use App\Models\ShortUrl;
use Illuminate\Support\Str;

class UrlShortenerService
{
    public function shorten(string $url): ShortUrl
    {
        return ShortUrl::create([
            "code" => $this->generateUniqueCode(),
            "original_url" => $url,
        ]);
    }

    protected function generateUniqueCode(int $length = 6): string
    {
        do {
            $code = Str::random($length);
        } while (ShortUrl::where("code", $code)->exists());

        return $code;
    }

    public function getOriginalUrl(string $code): ?string
    {
        $shortUrl = ShortUrl::where("code", $code)->first();

        return $shortUrl?->original_url;
    }
}
