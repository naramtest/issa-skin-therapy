<?php

namespace App\Helpers\Filament;

use Illuminate\Database\Eloquent\Model;

class MediaFilamentForm
{
    public static function fill(string $field, array $data, Model $media): array
    {
        $image = $media->getFirstMedia($field);
        $data[$field . "_caption"] = $image->custom_properties["caption"] ?? "";
        $data[$field . "_title"] = $image->custom_properties["title"] ?? "";
        $data[$field . "_alt"] = $image->custom_properties["alt"] ?? "";

        return $data;
    }

    public static function save(string $field, array $data, Model $media): void
    {
        $image = $media->getFirstMedia($field);
        if (!$image) {
            return;
        }
        $properties = [
            $field . "_alt" => "alt",
            $field . "_title" => "title",
            $field . "_caption" => "caption",
        ];

        foreach ($properties as $key => $property) {
            if (!empty($data[$key])) {
                $image->setCustomProperty($property, $data[$key]);
            }
        }
        $image->save();
    }

    public static function unset(string $field, array $data): array
    {
        unset(
            $data[$field . "_alt"],
            $data[$field . "_title"],
            $data[$field . "_caption"]
        );

        return $data;
    }
}
