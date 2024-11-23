<?php

namespace App\Helpers\Filament\Post;

use App\Helpers\Filament\Component\FullImageSectionUpload;
use App\Helpers\Filament\Post\Components\AssociationsSection;
use App\Helpers\Filament\Post\Components\ContentTab;
use App\Helpers\Filament\Post\Components\SeoTab;
use App\Helpers\Filament\Post\Components\StatusSection;
use Exception;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Form;

class PostForm
{
    /**
     * @throws Exception
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Tabs::make("Tabs")->tabs([
                            ContentTab::make(),
                            Tabs\Tab::make(__("dashboard.Media"))
                                ->icon("gmdi-image-o")
                                ->columns()
                                ->schema(
                                    FullImageSectionUpload::make(
                                        config("const.media.featured"),
                                        __("dashboard.Featured"),
                                        config("const.media.featured")
                                    )
                                ),
                            SeoTab::make(),
                        ]),
                    ])
                    ->columnSpan(2),
                Group::make()
                    ->schema([
                        StatusSection::make(),
                        AssociationsSection::make(),
                        //                        SeoSection::make(),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
    }
}
