<?php

namespace App\Helpers\Filament\Component;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FullImageSectionUpload
{
    public static function make(
        $field,
        $label,
        $collection,
        $hiddenLabel = true,
        $saveDraft = true
    ): array {
        $imageArray = [
            SpatieMediaLibraryFileUpload::make($field)
                ->label($label)
                ->collection($collection)
                ->hiddenLabel($hiddenLabel)
                ->getUploadedFileNameForStorageUsing(
                    fn(TemporaryUploadedFile $file, Get $get) => Str::slug(
                        $get($field . "_title")
                    ) .
                        "." .
                        $file->extension()
                )
                ->customProperties(
                    fn(Get $get): array => [
                        "caption" => $get($field . "_caption"),
                        "alt" => $get($field . "_alt"),
                        "title" => $get($field . "_title"),
                    ]
                )
                ->afterStateUpdated(function ($state, $set) use ($saveDraft) {
                    if (!$saveDraft) {
                        return;
                    }
                    if ($state instanceof TemporaryUploadedFile) {
                        $set(
                            "temp_image_path",
                            $state->getClientOriginalPath()
                        );
                    }
                })
                ->columnSpan(1)
                ->imageEditor()
                ->image()
                ->live()
                ->downloadable()
                ->maxSize(5120)
                ->imageEditorAspectRatios([null, "16:9", "4:3", "1:1"]),
            Group::make()
                ->schema([
                    TextInput::make($field . "_title")
                        ->label(__("dashboard.Title"))
                        ->inlineLabel()
                        ->required(fn(Get $get) => $get($field)),
                    TextInput::make($field . "_alt")
                        ->label(__("dashboard.alt"))
                        ->inlineLabel()
                        ->required(fn(Get $get) => $get($field)),
                    TextInput::make($field . "_caption")
                        ->inlineLabel()
                        ->label(__("dashboard.Caption")),
                    // Used it to auto save
                ])
                ->columnSpan(1),
        ];
        if ($saveDraft) {
            $imageArray[] = Hidden::make("temp_image_path");
        }
        return $imageArray;
    }
}
