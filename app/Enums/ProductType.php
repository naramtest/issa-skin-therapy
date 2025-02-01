<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductType: string implements hasLabel
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

    public function getLabel(): string
    {
        return match ($this) {
            self::PRODUCT => "Product",
            self::BUNDLE => "Bundle",
        };
    }
}
