<?php

namespace App\Services\Media;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class GetSpatieImage
{
    private Model $model;

    private ?string $collection;

    private ?string $conversion;

    private ?string $defaultImage = "storage/images/blog.jpeg";

    private string $class = "";

    private ?int $width = null;

    private ?int $height = null;

    private bool $lazy = false;

    final public function __construct(Model $model, string $collection)
    {
        $this->model = $model;
        $this->conversion = config("const.media.optimized");
        $this->collection = $collection;
    }

    public static function make(Model $model, string $collection): static
    {
        return app(static::class, [
            "model" => $model,
            "collection" => $collection,
        ]);
    }

    public function defaultImage(?string $defaultImage): static
    {
        $this->defaultImage = $defaultImage;

        return $this;
    }

    public function conversion(?string $conversion): static
    {
        $this->conversion = $conversion;

        return $this;
    }

    public function class(string $class): static
    {
        $this->class = $class;

        return $this;
    }

    public function width(?int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function height(?int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function lazy(bool $lazy): static
    {
        $this->lazy = $lazy;

        return $this;
    }

    public function getResponsiveCollection(): array
    {
        $images = [];
        $mediaCollection = $this->model->getMedia($this->collection);
        foreach ($mediaCollection as $media) {
            $images[] = self::getImageObject($media);
        }

        return $images;
    }

    private function getImageObject(?Media $image): string
    {
        if (!$image) {
            return $this->getDefImage();
        }

        $extraAttributes = [
            "alt" => $image->getCustomProperty("alt") ?? __("views.Image"),
            "class" => $this->class,
        ];

        if ($this->lazy) {
            $extraAttributes["loading"] = "lazy";
        }

        if ($this->width) {
            $extraAttributes["width"] = $this->width;
        }

        if ($this->height) {
            $extraAttributes["height"] = $this->height;
        }

        return $image
            ->img(
                $this->conversion &&
                $image->hasGeneratedConversion($this->conversion)
                    ? $this->conversion
                    : "",
                $extraAttributes
            )
            ->toHtml();
    }

    /*   create a collection of image
        [
          'srcset' => image srcset,
           'url' => image url,
       ]
    I use this in project gallery
     */

    /**
     * @return string
     */
    public function getDefImage(): string
    {
        return ' <img
                    class="' .
            $this->class .
            '"
                    src="' .
            asset($this->defaultImage) .
            '"
                    alt="{{ __("views.Project Image") }}"
                />';
    }

    public function getImageCollection(): array
    {
        $images = [];
        $mediaCollection = $this->model->getMedia($this->collection);
        foreach ($mediaCollection as $media) {
            $images[] = [
                "srcset" => self::getImageSrcset($media),
                "url" => self::imageUrl($media),
            ];
        }

        return $images;
    }

    private function getImageSrcset(?Media $image): string
    {
        if (!$image) {
            return "";
        }

        return $image->getSrcset(
            $this->conversion &&
            $image->hasGeneratedConversion($this->conversion)
                ? $this->conversion
                : ""
        );
    }

    public function imageUrl(?Media $image): string
    {
        if (!$image) {
            return "";
        }

        return $image->getAvailableUrl([$this->conversion ?? ""]);
    }

    public function getImageUrl(): string
    {
        $image = $this->model->getFirstMedia($this->collection);
        if (!$image) {
            return "";
        }

        return $image->getAvailableUrl([$this->conversion ?? ""]);
    }

    public function getResponsiveImage(): string
    {
        $image = $this->model->getFirstMedia($this->collection);
        return self::getImageObject($image);
    }

    public function getImgElement($class = ""): string
    {
        $image = $this->model->getFirstMedia($this->collection);
        if (!$image) {
            return $this->getDefImage();
        }
        return sprintf(
            '<img src="%s" class="%s" alt="%s"/>',
            self::imageUrl($image),
            htmlspecialchars($class),
            $image->getCustomProperty("alt") ?? __("views.Image")
        );
    }
}
