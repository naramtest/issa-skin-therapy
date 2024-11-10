<?php

namespace App\Enums;

enum ProductStatus: string
{
    case DRAFT = "draft";
    case PUBLISHED = "published";

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => __("dashboard.Draft"),
            self::PUBLISHED => __("dashboard.Published"),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::DRAFT => "gray",
            self::PUBLISHED => "success",
        };
    }
}
