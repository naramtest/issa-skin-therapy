<?php

namespace App\Helpers\Filament\Purchasable\Form;

use App\Enums\ProductStatus;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ToggleButtons;

class StatusSection
{
    public static function make(array $array)
    {
        return Section::make(__("dashboard.Status"))->schema([
            ToggleButtons::make("status")
                ->options(ProductStatus::class)
                ->inline()
                ->default(ProductStatus::PUBLISHED)
                ->extraInputAttributes([
                    "class" => "toggle-button",
                ])
                ->live()
                ->hiddenLabel()
                ->grouped(),
            DateTimePicker::make("published_at")
                ->maxDate(now()->addDay())
                ->label(__("dashboard.Published At"))
                ->live()
                ->default(function ($operation) {
                    if ($operation == "create") {
                        return now();
                    }

                    return null;
                })
                ->minDate(function ($operation) {
                    if ($operation == "create") {
                        return Carbon::today();
                    }

                    return null;
                })
                ->visible(
                    fn(callable $get) => $get("status") !== ProductStatus::DRAFT
                )
                ->displayFormat("d-m-Y-H-i-s"),
            ...$array,
        ]);
    }
}
