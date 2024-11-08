<?php

namespace App\Services\Filament\Component;

use Filament\Forms\Get;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class CustomTinyEditor
{
    public static function TinyEditor(
        string $columnName,
        ?string $label = null
    ): TinyEditor {
        return TinyEditor::make($columnName)
            ->label($label)
            ->autofocus(false)
            ->toolbarSticky(true)
            ->language(App::getLocale())
            ->setConvertUrls(false)
            ->setRelativeUrls(true)
            ->minHeight(300)
            ->fileAttachmentsDirectory(function (Get $get) {
                return 'body-attachments/'.Str::substr($get('slug'), 0, 8);
            })
            ->profile('default');
    }
}
