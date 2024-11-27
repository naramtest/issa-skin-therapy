<?php

namespace App\Helpers\Media;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageGetter
{
    public static function responsiveFeaturedImg(
        Model&HasMedia $model,
        ?string $conversion = null,
        string $class = "",
        ?int $width = null,
        ?int $height = null,
        bool $lazy = false
    ): string {
        $image = $model->getFirstMedia(config("const.media.featured"));
        if (!$image) {
            return "";
        }
        return self::responsiveImgElement(
            $image,
            $conversion,
            $class,
            $width,
            $height,
            $lazy
        );
    }

    public static function responsiveImgElement(
        Media $image,
        ?string $conversion = null,
        string $class = "",
        ?int $width = null,
        ?int $height = null,
        bool $lazy = false
    ): string {
        $extraAttributes = [
            "alt" => $image->getCustomProperty("alt") ?? __("views.Image"),
            "class" => $class,
        ];

        if ($lazy) {
            $extraAttributes["loading"] = "lazy";
        }

        if ($width) {
            $extraAttributes["width"] = $width;
        }

        if ($height) {
            $extraAttributes["height"] = $height;
        }

        $conversion = $conversion ?? config("const.media.optimized");
        return $image
            ->img(
                $conversion && $image->hasGeneratedConversion($conversion)
                    ? $conversion
                    : "",
                $extraAttributes
            )
            ->toHtml();
    }

    public static function getMediaUrl(Model&HasMedia $model)
    {
        $image = $model->getFirstMedia(config("const.media.featured"));
        if (!$image) {
            return "";
        }
        return $image->getAvailableUrl([config("const.media.optimized")]);
    }
}
