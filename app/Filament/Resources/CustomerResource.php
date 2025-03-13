<?php

namespace App\Filament\Resources;

use App\Filament\Exports\CustomerExporter;
use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Helpers\Filament\Component\CustomDateColumn;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput as MoneyField;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;

class CustomerResource extends Resource
{
    //TODO : translate
    protected static ?string $model = Customer::class;
    protected static ?string $navigationIcon = "gmdi-people-o";
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__("store.Customer Information"))
                ->schema([
                    Forms\Components\TextInput::make("first_name")
                        ->label(__("store.First Name"))
                        ->required()
                        ->inlineLabel()
                        ->maxLength(255),
                    Forms\Components\TextInput::make("last_name")
                        ->label("Last Name")
                        ->required()
                        ->inlineLabel()
                        ->maxLength(255),
                    Forms\Components\TextInput::make("email")
                        ->label("Email")
                        ->email()
                        ->required()
                        ->inlineLabel()
                        ->disabled()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                ])
                ->columnSpan(1),
            Forms\Components\Group::make([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Toggle::make("is_registered")
                        ->label("Is Registered User")
                        ->default(false),
                    ViewField::make("id")
                        ->label("User Name")
                        ->view("filament.forms.components.doctor-field")
                        ->inlineLabel()
                        ->columnSpan(1),
                ]),
                Forms\Components\Section::make()
                    ->schema([
                        MoneyField::make("total_spent")
                            ->label("Total Spent")
                            ->disabled(),
                        Forms\Components\TextInput::make("orders_count")
                            ->label("Orders Count")
                            ->numeric()
                            ->disabled(),
                        Forms\Components\DatePicker::make("last_order_at")
                            ->label("Last Order Date")
                            ->disabled(),
                    ])
                    ->columns(3),
            ])
                ->columns()
                ->columnSpan(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort("created_at", "desc")
            ->columns([
                Tables\Columns\TextColumn::make("full_name")
                    ->label("Name")
                    ->searchable(["first_name", "last_name"]),
                Tables\Columns\TextColumn::make("email")
                    ->searchable()
                    ->copyable(),
                Tables\Columns\IconColumn::make("is_registered")
                    ->label("Registered")
                    ->boolean(),
                Tables\Columns\TextColumn::make("orders_count")
                    ->label("Orders")
                    ->sortable()
                    ->alignCenter(),
                MoneyColumn::make("total_spent")->sortable(),
                CustomDateColumn::make("last_order_at")
                    ->label("Last Order")
                    ->sortable(),
                CustomDateColumn::make("created_at")
                    ->label("Joined")
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("is_registered")->options([
                    true => "Registered",
                    false => "Guest",
                ]),
                DateRangeFilter::make("created_at")->label("Joined Date"),
                DateRangeFilter::make("last_order_at")->label(
                    "Last Order Date"
                ),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([
                ExportBulkAction::make()->exporter(CustomerExporter::class),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrdersRelationManager::make(),
            RelationManagers\AddressesRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListCustomers::route("/"),
            "create" => Pages\CreateCustomer::route("/create"),
            "edit" => Pages\EditCustomer::route("/{record}/edit"),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __("store.Customers");
    }

    public static function getModelLabel(): string
    {
        return __("store.Customer");
    }

    public static function getPluralModelLabel(): string
    {
        return __("store.Customers");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("store.Shop");
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(["user"]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ["first_name", "last_name", "email", "user.name", "user.email"];
    }
}
