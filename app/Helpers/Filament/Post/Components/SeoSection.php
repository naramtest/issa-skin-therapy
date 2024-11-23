<?php

namespace App\Helpers\Filament\Post\Components;

use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Forms\Components\SeoScan;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;

class SeoSection
{
    public static function make()
    {
        return Section::make("SEO Score")
            ->schema([
                SeoScan::make("seo")
                    ->hiddenLabel()
                    ->setEditPost(function (EditPost $livewire) {
                        return $livewire;
                    })
                    ->key("seoScan"),
            ])
            ->headerActions([
                Action::make("scan")
                    ->icon("gmdi-radar-o")
                    ->action(function (Section $component) {
                        $component
                            ->getContainer()
                            ->getComponent("seoScan")
                            ->startScan();
                    }),
            ]);
    }
}
