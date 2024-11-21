<?php

namespace App\Traits;

use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasPurchasableMedia
{
    use InteractsWithMedia;

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion(config("const.media.thumbnail"))
            ->format("webp")
            ->performOnCollections(config("const.media.featured"))
            ->width(400)
            ->height(400)
            ->optimize()
            ->quality(70);
        $this->addMediaConversion(config("const.media.optimized"))
            ->format("webp")
            ->optimize()
            ->withResponsiveImages();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(config("const.media.featured"))->singleFile();

        $this->addMediaCollection(config("const.media.gallery"));
    }
}
