<?php

namespace App\Helpers\Filament\Post\Components;

use App\Enums\CategoryType;
use App\Enums\ProductStatus;
use App\Helpers\Filament\Component\CategoryFilament;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;

class AssociationsSection
{
    public static function make()
    {
        return Section::make(__("dashboard.Association"))->schema([
            CategoryFilament::Select(CategoryType::POST, false)->required(
                function (Get $get) {
                    return $get("status") != ProductStatus::DRAFT->value;
                }
            ),

            Toggle::make("is_featured")
                ->label(__("dashboard.Featured"))
                ->inline(false)
                ->onIcon("gmdi-star-o"),
        ]);
    }
}
