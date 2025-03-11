<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationIcon = "gmdi-contact-mail-o";

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__("store.Details"))
                ->schema([
                    Forms\Components\TextInput::make("name")
                        ->name(__("store.Name"))
                        ->required()
                        ->maxLength(100)
                        ->columnSpan(1)
                        ->disabled(),

                    Forms\Components\TextInput::make("email")
                        ->name(__("store.Email"))
                        ->email()
                        ->required()
                        ->columnSpan(1)
                        ->maxLength(100)
                        ->disabled(),

                    Forms\Components\TextInput::make("phone")
                        ->name(__("store.Phone Number"))
                        ->maxLength(20)
                        ->columnSpan(1)
                        ->disabled(),

                    Forms\Components\TextInput::make("subject")
                        ->name(__("dashboard.Subject"))
                        ->required()
                        ->columnSpan(1)
                        ->maxLength(100)
                        ->disabled(),

                    Forms\Components\Textarea::make("message")
                        ->name(__("store.Message"))
                        ->required()
                        ->columnSpan(1)
                        ->disabled()
                        ->rows(4)
                        ->columnSpanFull(),

                    Forms\Components\Toggle::make("is_read")
                        ->required()
                        ->label(__("store.Is Read")),
                ])
                ->columns(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")
                    ->label(__("store.Name"))
                    ->searchable(),
                Tables\Columns\TextColumn::make("email")
                    ->label(__("store.Email"))
                    ->searchable(),
                Tables\Columns\TextColumn::make("subject")
                    ->Label(__("dashboard.Subject"))
                    ->searchable()
                    ->limit(30),
                Tables\Columns\IconColumn::make("is_read")
                    ->label(__("store.Is Read"))
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make("created_at")
                    ->label(__("dashboard.Created At"))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([Tables\Filters\TernaryFilter::make("is_read")])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make("markAsRead")
                        ->label(__("store.Mark as Read"))
                        ->action(
                            fn(Collection $records) => $records->each->update([
                                "is_read" => true,
                            ])
                        )
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort("created_at", "desc");
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListContactMessages::route("/"),
            "edit" => Pages\EditContactMessage::route("/{record}/edit"),
        ];
    }

    public static function getLabel(): ?string
    {
        return __("store.Contact Message");
    }

    public static function getModelLabel(): string
    {
        return __("store.Contact Message");
    }

    public static function getNavigationLabel(): string
    {
        return __("store.Contact Messages");
    }

    public static function getPluralLabel(): ?string
    {
        return __("store.Contact Messages");
    }

    public static function getPluralModelLabel(): string
    {
        return __("store.Contact Messages");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("store.Marketing");
    }
}
