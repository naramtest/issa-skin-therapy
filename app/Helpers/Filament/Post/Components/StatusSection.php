<?php

namespace App\Helpers\Filament\Post\Components;

use App\Enums\ProductStatus;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;

class StatusSection
{
    public static function make()
    {
        return Section::make(__("dashboard.Status"))
            ->headerActions([
                Action::make("preview")
                    ->label(__("dashboard.Preview"))
                    ->view("filament.preview-button", [
                        "route" => function ($livewire) {
                            return route("post.preview", [
                                "id" => $livewire->record->id,
                            ]);
                        },
                    ])
                    ->hidden(fn($operation) => $operation == "create"),
            ])
            ->schema([
                ToggleButtons::make("status")
                    ->options(ProductStatus::class)
                    ->inline()
                    ->default(function ($operation) {
                        if ($operation == "create") {
                            return ProductStatus::PUBLISHED;
                        }

                        return null;
                    })
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
                    ->hidden(function (Get $get, $operation) {
                        if ($operation == "create") {
                            return false;
                        }

                        return $get("status") !=
                            ProductStatus::PUBLISHED->value;
                    })
                    ->displayFormat("d-m-Y-H-i-s"),
            ]);
    }
}
