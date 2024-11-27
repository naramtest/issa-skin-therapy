<?php

namespace App\Helpers\Filament\Post;

use App\Enums\ProductStatus;
use App\Models\Post;
use Exception;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class PostTable
{
    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("title")
                    ->label(__("dashboard.Title"))
                    ->searchable()
                    ->limit(40)
                    ->getStateUsing(function (Post $record): string {
                        return html_entity_decode(
                            $record->title,
                            ENT_QUOTES,
                            "UTF-8"
                        );
                    })
                    ->withTooltip()
                    ->sortable(),
                TextColumn::make("author.name")
                    ->label(__("dashboard.Author"))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                //                TextColumn::make('seoScores.score')
                //                    ->label(__('dashboard.seo-score'))
                //                    ->sortable()
                //                    ->toggleable(),
                //            TODO: add seo score
                TextColumn::make("status")
                    ->label(__("dashboard.Status"))
                    ->badge()
                    ->color(
                        fn($state): string => match ($state) {
                            ProductStatus::DRAFT => "gray",
                            ProductStatus::PUBLISHED => "success",
                        }
                    )
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            ProductStatus::DRAFT => __("dashboard.Draft"),
                            ProductStatus::PUBLISHED => __(
                                "dashboard.Published"
                            ),
                        };
                    }),
                TextColumn::make("categories.name")
                    ->label(__("dashboard.Categories"))
                    ->searchable()
                    ->sortable()
                    ->toggleable(true, true),
                TextColumn::make("published_at")
                    ->label(__(__("dashboard.Published At")))
                    ->dateTime("M j, Y")
                    ->sortable()
                    ->toggleable(true, true),
                TextColumn::make("created_at")
                    ->label(__("dashboard.Created At"))
                    ->dateTime("M j, Y")
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                TrashedFilter::make(),

                DateRangeFilter::make("created_at")->label(
                    __("dashboard.Created At")
                ),
                SelectFilter::make("categories")
                    ->multiple()
                    ->preload()
                    ->relationship("categories", "name")
                    ->label(__("dashboard.Categories")),
                Filter::make("is_visible")
                    ->label(__("dashboard.Published"))
                    ->toggle(),
                SelectFilter::make("author")
                    ->relationship("author", "name")
                    ->label(__("dashboard.Author")),
            ])
            ->defaultSort("created_at", "desc")
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make("show")
                    ->label(__("dashboard.Show"))
                    ->color("success")
                    ->icon("gmdi-visibility-o")
                    ->hidden(
                        fn(Post $record) => $record->status !==
                            ProductStatus::PUBLISHED
                    )
                    ->url(
                        fn(Post $record) => route("posts.show", [
                            "post" => $record->slug,
                        ]),
                        true
                    ),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
