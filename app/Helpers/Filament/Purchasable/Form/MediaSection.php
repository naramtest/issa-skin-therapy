<?php

namespace App\Helpers\Filament\Purchasable\Form;

use App\Services\Filament\Component\FullImageSectionUpload;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;

class MediaSection
{
    public static function make()
    {
        return Tabs\Tab::make(__("dashboard.Media"))
            ->icon("gmdi-image-o")
            ->columns()
            ->schema([
                Fieldset::make(__("dashboard.Featured"))->schema(
                    FullImageSectionUpload::make(
                        config("const.media.featured"),
                        __("dashboard.Featured"),
                        config("const.media.featured")
                    )
                ),

                Fieldset::make(__("dashboard.Gallery"))->schema([
                    SpatieMediaLibraryFileUpload::make(
                        config("const.media.gallery")
                    )
                        ->hiddenLabel()
                        ->collection(config("const.media.gallery"))
                        ->columnSpan(2)
                        ->imageEditor()
                        ->image()
                        ->multiple()
                        ->live()
                        ->downloadable()
                        ->maxSize(5120)
                        ->imageEditorAspectRatios([null, "16:9", "4:3", "1:1"]),
                ]),
            ]);
    }
}
