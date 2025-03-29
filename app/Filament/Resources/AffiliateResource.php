<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliateResource\Pages;
use App\Filament\Resources\AffiliateResource\RelationManagers;
use App\Models\Affiliate;
use App\Models\User;
use App\Services\Currency\CurrencyHelper;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AffiliateResource extends Resource
{
    protected static ?string $model = Affiliate::class;

    protected static ?string $navigationIcon = "heroicon-o-users";

    protected static ?string $navigationGroup = "Marketing";

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make("user_id")
                ->label(__("dashboard.User"))
                ->options(User::all()->pluck("name", "id"))
                ->searchable()
                ->required(),
            Forms\Components\TextInput::make("phone")
                ->label(__("dashboard.Phone"))
                ->tel(),
            Forms\Components\TextInput::make("slug")
                ->label(__("dashboard.Slug"))
                ->required()
                ->unique(Affiliate::class, "slug", ignoreRecord: true)
                ->maxLength(255)
                ->afterStateUpdated(function (
                    Forms\Components\TextInput $component,
                    $state
                ) {
                    $component->state(Str::slug($state));
                }),
            Forms\Components\Textarea::make("about")
                ->label(__("dashboard.About"))
                ->maxLength(65535)
                ->columnSpanFull(),
            Forms\Components\Toggle::make("status")
                ->label(__("dashboard.Active"))
                ->default(true)
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("user.name")
                    ->label(__("dashboard.User"))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("phone")
                    ->label(__("dashboard.Phone"))
                    ->searchable(),
                Tables\Columns\TextColumn::make("slug")
                    ->label(__("dashboard.Slug"))
                    ->searchable(),
                Tables\Columns\IconColumn::make("status")
                    ->label(__("dashboard.Status"))
                    ->boolean(),
                Tables\Columns\TextColumn::make("money_total_commission")
                    ->label(__("dashboard.Total Commission"))
                    ->formatStateUsing(
                        fn($state) => CurrencyHelper::format($state)
                    )
                    ->sortable(["total_commission"]),
                Tables\Columns\TextColumn::make("money_paid_commission")
                    ->label(__("dashboard.Paid Commission"))
                    ->formatStateUsing(
                        fn($state) => CurrencyHelper::format($state)
                    )
                    ->sortable(["paid_commission"]),
                Tables\Columns\TextColumn::make("money_unpaid_commission")
                    ->label(__("dashboard.Unpaid Commission"))
                    ->formatStateUsing(
                        fn($state) => CurrencyHelper::format($state)
                    ),
                Tables\Columns\TextColumn::make("created_at")
                    ->label(__("dashboard.Created At"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make("updated_at")
                    ->label(__("dashboard.Updated At"))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("status")->options([
                    "1" => __("dashboard.Active"),
                    "0" => __("dashboard.Inactive"),
                ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CouponsRelationManager::class,
            RelationManagers\CommissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListAffiliates::route("/"),
            "create" => Pages\CreateAffiliate::route("/create"),
            "edit" => Pages\EditAffiliate::route("/{record}/edit"),
        ];
    }
}
