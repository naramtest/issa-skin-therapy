<?php

namespace App\Enums;

enum ProductType: string
{
    case PRODUCT = "product";
    case BUNDLE = "bundle";

    public static function fromString(string $value): ?self
    {
        return match (strtolower($value)) {
            "product" => self::PRODUCT,
            "bundle" => self::BUNDLE,
            default => null,
        };
    }
}
