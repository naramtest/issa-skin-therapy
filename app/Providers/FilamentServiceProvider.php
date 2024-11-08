<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Forms\Components\Field;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        FilamentView::registerRenderHook(
            "panels::global-search.after",
            fn(): View => view("filament.preview-top-bar")
        );
        Filament::serving(function () {
            Field::macro("translate", function (bool $isInline = false) {
                if ($isInline) {
                    $this->helperText(
                        view("filament.custom-helper-text", [
                            "icon" => "gmdi-translate-o",
                            "text" => __("dashboard.Translatable"),
                        ])
                    );
                } else {
                    $this->hint(__("dashboard.Translatable"));
                    $this->hintIcon("gmdi-translate-o");
                }

                return $this;
            });
            Field::macro("counter", function ($field, $max) {
                $this->hint(
                    view("filament.char-counter", [
                        "field" => $field,
                        "max" => $max,
                    ])
                );

                return $this;
            });
            TextColumn::macro("withTooltip", function () {
                return $this->tooltip(function (TextColumn $column): ?string {
                    $state = $column->getState();
                    if (strlen($state) <= $column->getCharacterLimit()) {
                        return null;
                    }

                    return $state;
                });
            });
        });
    }
}
