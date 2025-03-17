<?php

namespace App\Filament\Resources;

use App\Enums\SubscriberStatus;
use App\Filament\Exports\SubscriberExporter;
use App\Filament\Resources\SubscriberResource\Pages;
use App\Models\Subscriber;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class SubscriberResource extends Resource
{
    protected static ?string $model = Subscriber::class;
    protected static ?string $navigationIcon = "heroicon-o-envelope";
    protected static ?int $navigationSort = 5;
    protected static ?string $recordTitleAttribute = "email";

    public static function getNavigationGroup(): ?string
    {
        return __("store.Marketing");
    }

    public static function getNavigationLabel(): string
    {
        return __("store.Subscribers");
    }

    public static function getModelLabel(): string
    {
        return __("store.Subscriber");
    }

    public static function getPluralModelLabel(): string
    {
        return __("store.Subscribers");
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make("email")
                    ->label(__("store.Email"))
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make("status")
                    ->label(__("dashboard.Status"))
                    ->badge()
                    ->sortable(),
                TextColumn::make("created_at")
                    ->dateTime("M j, Y")
                    ->sortable()
                    ->label(__("store.Subscribed On")),
            ])
            ->filters([
                SelectFilter::make("status")->options(SubscriberStatus::class),
                DateRangeFilter::make("created_at")->label(
                    __("store.Subscription Date")
                ),
            ])
            ->actions([ViewAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                ExportBulkAction::make()->exporter(SubscriberExporter::class),
            ])
            ->defaultSort("created_at", "desc");
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make(__("store.Subscriber Information"))
                ->schema([
                    TextEntry::make("email")->label(__("store.Email")),
                    TextEntry::make("status")
                        ->badge()
                        ->label(__("dashboard.Status")),
                    TextEntry::make("created_at")
                        ->dateTime()
                        ->label(__("store.Subscribed On")),
                    TextEntry::make("updated_at")
                        ->dateTime()
                        ->label(__("store.Last Updated")),
                ])
                ->columns(2),
        ]);
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListSubscribers::route("/"),
            "view" => Pages\ViewSubscriber::route("/{record}"),
        ];
    }

    public static function getLabel(): ?string
    {
        return __("store.Subscriber");
    }

    public static function getPluralLabel(): ?string
    {
        return __("store.Subscribers");
    }
}
