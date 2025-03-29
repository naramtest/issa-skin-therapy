<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AffiliateResource\Pages;
use App\Filament\Resources\AffiliateResource\RelationManagers;
use App\Models\Affiliate;
use App\Services\Currency\CurrencyHelper;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class AffiliateResource extends Resource
{
    protected static ?string $model = Affiliate::class;

    protected static ?string $navigationIcon = "heroicon-o-users";

    protected static ?string $navigationGroup = "Marketing";

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make()
                ->columnSpanFull()
                ->columns()
                ->tabs([
                    Tab::make("User")
                        ->label(__("store.User Information"))
                        ->icon("gmdi-person")
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
                                            fn($operation) => $operation ===
                                                "create"
                                        )
                                        ->dehydrated(
                                            fn($operation, $state) => !is_null(
                                                $state
                                            )
                                        )
                                        ->password()
                                        ->revealable(),
                                ]),
                        ]),
                    Tab::make("Details")
                        ->label(__("store.Details"))
                        ->icon("gmdi-info-o")
                        ->schema([
                            Group::make()->schema([
                                Forms\Components\Toggle::make("status")
                                    ->label(__("store.Active"))
                                    ->default(true)
                                    ->required(),
                                PhoneInput::make("phone")->label(
                                    __("store.phone")
                                ),
                                Forms\Components\TextInput::make("slug")
                                    ->label(__("dashboard.Permalink"))
                                    ->required()
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
