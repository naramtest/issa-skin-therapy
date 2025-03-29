<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliateResource\Pages;
use App\Filament\Resources\AffiliateResource\RelationManagers;
use App\Models\Affiliate;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class AffiliateResource extends Resource
{
    protected static ?string $model = Affiliate::class;

    protected static ?string $navigationIcon = "heroicon-o-users";

    public static function form(Form $form): Form
    {
        return $form->columns()->schema([
            Forms\Components\Section::make("User")
                ->label(__("store.User Information"))
                ->icon("gmdi-person")
                ->columnSpan(1)
                ->schema([
                    Group::make()
                        ->relationship("user")
                        ->schema([
                            TextInput::make("name")
                                ->required()
                                ->live(onBlur: true)
                                ->label(__("store.Name"))
                                ->afterStateUpdated(function (
                                    Forms\Components\TextInput $component,
                                    Forms\Set $set,
                                    $state
                                ) {
                                    $set("../slug", Str::slug($state));
                                }),

                            TextInput::make("email")
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->label(__("store.Email")),
                            TextInput::make("password")
                                ->label(__("dashboard.password"))
                                ->required(
                                    fn($operation) => $operation === "create"
                                )
                                ->dehydrated(
                                    fn($operation, $state) => !is_null($state)
                                )
                                ->password()
                                ->revealable(),
                        ]),
                ]),
            Forms\Components\Section::make("Details")
                ->label(__("store.Details"))
                ->columnSpan(1)
                ->icon("gmdi-info-o")
                ->schema([
                    Group::make()->schema([
                        Forms\Components\Toggle::make("status")
                            ->label(__("store.Active"))
                            ->default(true)
                            ->required(),
                        PhoneInput::make("phone")
                            ->label(__("store.phone"))
                            ->inlineLabel(),
                        Forms\Components\TextInput::make("slug")
                            ->label(__("dashboard.Permalink"))
                            ->required()
                            ->inlineLabel()
                            ->live()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->afterStateUpdated(function (
                                Forms\Components\TextInput $component,
                                Forms\Set $set,
                                $state
                            ) {
                                $component->state(Str::slug($state));
                            }),
                        Forms\Components\Textarea::make("about")
                            ->label(__("store.About"))
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
                ]),
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
                    ->label(__("store.phone"))
                    ->searchable(),
                Tables\Columns\TextColumn::make("slug")
                    ->label(__("dashboard.Permalink"))
                    ->searchable(),
                Tables\Columns\IconColumn::make("status")
                    ->label(__("dashboard.Status"))
                    ->boolean(),
                MoneyColumn::make("total_commission")
                    ->label(__("dashboard.Total Commission"))
                    ->sortable(),
                MoneyColumn::make("paid_commission")
                    ->label(__("dashboard.Paid Commission"))
                    ->sortable(),

                Tables\Columns\TextColumn::make("created_at")
                    ->label(__("dashboard.Created At"))
                    ->dateTime("d M , Y")
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort("created_at", "DESC")
            ->filters([
                Tables\Filters\SelectFilter::make("status")->options([
                    "1" => __("dashboard.Active"),
                    "0" => __("dashboard.Inactive"),
                ]),
                DateRangeFilter::make("created_at")->label(
                    __("dashboard.Created At")
                ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

    public static function getNavigationGroup(): ?string
    {
        return __("store.Marketing");
    }

    public static function getNavigationLabel(): string
    {
        return __("dashboard.Affiliates");
    }

    public static function getModelLabel(): string
    {
        return __("dashboard.Affiliate");
    }

    public static function getPluralModelLabel(): string
    {
        return __("dashboard.Affiliates");
    }

    public static function getLabel(): ?string
    {
        return __("dashboard.Affiliate");
    }

    public static function getPluralLabel(): ?string
    {
        return __("dashboard.Affiliates");
    }
}
