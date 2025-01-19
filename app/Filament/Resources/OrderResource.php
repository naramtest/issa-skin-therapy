<?php

namespace App\Filament\Resources;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = "gmdi-shopping-cart-o";
    protected static ?int $navigationSort = 4;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("order_number")
                    ->label(__("store.Order Number"))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make("customer.full_name")
                    ->label(__("store.Customer"))
                    ->searchable(["first_name", "last_name"]),
                MoneyColumn::make("total")
                    ->sortable()
                    ->label(__("store.Total")),

                Tables\Columns\TextColumn::make("status")->badge()->sortable(),
                Tables\Columns\TextColumn::make("payment_status")->badge(),
                Tables\Columns\TextColumn::make("created_at")
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("status")->options(
                    OrderStatus::class
                ),
                Tables\Filters\SelectFilter::make("payment_status")->options(
                    PaymentStatus::class
                ),
                Tables\Filters\Filter::make("created_at")
                    ->form([
                        Forms\Components\DatePicker::make("created_from"),
                        Forms\Components\DatePicker::make("created_until"),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data["created_from"],
                                fn(
                                    Builder $query,
                                    $date
                                ): Builder => $query->whereDate(
                                    "created_at",
                                    ">=",
                                    $date
                                )
                            )
                            ->when(
                                $data["created_until"],
                                fn(
                                    Builder $query,
                                    $date
                                ): Builder => $query->whereDate(
                                    "created_at",
                                    "<=",
                                    $date
                                )
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort("created_at", "desc");
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Grid::make([
                "default" => 1,
                "md" => 3,
            ])->schema([
                Tabs::make()
                    ->extraAttributes(["class" => "min-h-[42.5vh]"])
                    ->tabs([
                        Tabs\Tab::make("Order Details")
                            ->schema([
                                TextInput::make("order_number")
                                    ->prefix("#")
                                    ->label(__("dashboard.order_number"))
                                    ->columnSpan(1)
                                    ->disabled(),
                                MoneyInput::make("total")
                                    ->label(__("dashboard.products_price"))
                                    ->columnSpan(1)
                                    ->disabled(),
                                MoneyInput::make("final")
                                    ->label("Total")
                                    ->columnSpan(1)
                                    ->disabled(),
                                TextInput::make("payment_method")
                                    ->label("Payment Method")
                                    ->columnSpan(1)
                                    ->disabled(),
                                Select::make("payment_status")
                                    ->options(PaymentStatus::class)
                                    ->label("Payment Status")
                                    ->columnSpan(1)
                                    ->extraAttributes([
                                        "class" => "uppercase",
                                    ]),
                            ])
                            ->columns(3),
                        //                        Tabs\Tab::make("Customer Info")->schema([
                        //
                        //                            Group::make()
                        //                                ->columns(3)
                        //                                ->relationship("customer")
                        //                                ->schema([
                        //                                    Placeholder::make("name")
                        //                                        ->label(__("dashboard.name"))
                        //                                        ->columnSpan(1)
                        //                                        ->content(
                        //                                            fn(
                        //                                                Customer $record
                        //                                            ): string => $record->name
                        //                                        ),
                        //                                    Placeholder::make("email")
                        //                                        ->label(__("dashboard.email"))
                        //                                        ->columnSpan(1)
                        //                                        ->content(
                        //                                            fn(
                        //                                                Customer $record
                        //                                            ): string => $record->email
                        //                                        ),
                        //                                    Placeholder::make("city")
                        //                                        ->label(__("dashboard.city"))
                        //                                        ->columnSpan(1)
                        //                                        ->content(
                        //                                            fn(
                        //                                                Customer $record
                        //                                            ): string => $record->city
                        //                                        ),
                        //                                    Placeholder::make("area")
                        //                                        ->label(__("dashboard.area"))
                        //                                        ->columnSpan(1)
                        //                                        ->content(
                        //                                            fn(
                        //                                                Customer $record
                        //                                            ): string => $record->area
                        //                                        ),
                        //                                    Placeholder::make("street")
                        //                                        ->label(__("dashboard.street"))
                        //                                        ->columnSpan(1)
                        //                                        ->content(
                        //                                            fn(
                        //                                                Customer $record
                        //                                            ): string => $record->street
                        //                                        ),
                        //                                    Placeholder::make("building")
                        //                                        ->label(__("dashboard.building"))
                        //                                        ->columnSpan(1)
                        //                                        ->content(
                        //                                            fn(
                        //                                                Customer $record
                        //                                            ): string => $record->building
                        //                                        ),
                        //                                    Placeholder::make("apartment_no")
                        //                                        ->label(__("dashboard.apartment_no"))
                        //                                        ->columnSpan(1)
                        //                                        ->content(
                        //                                            fn(
                        //                                                Customer $record
                        //                                            ): string => $record->apartment_no
                        //                                        ),
                        //                                ]),
                        //                        ]),
                    ])
                    ->columnSpan(2),

                Section::make("Status")
                    ->columnSpan(1)
                    ->schema([
                        Placeholder::make("created_at")
                            ->label(__("dashboard.create_at"))
                            ->inlineLabel()
                            ->columnSpan(1)
                            ->content(
                                fn(
                                    Order $record
                                ): string => $record->created_at->toFormattedDateString()
                            ),
                        //                        ViewField::make("status")
                        //                            ->view("filament.forms.components.order-status")
                        //                            ->live()
                        //                            ->label("Order Status")
                        //                            ->inlineLabel(),
                    ])
                    ->footerActions([
                        //                        Action::make("Accept")
                        //                            ->extraAttributes(["class" => "flex-1"])
                        //                            ->label(__("dashboard.accept"))
                        //                            ->requiresConfirmation()
                        //                            ->color("success")
                        //                            ->disabled(
                        //                                fn(Order $record) => $record->status ==
                        //                                    OrderStatus::ACCEPTED
                        //                            )
                        //                            ->action(function (Order $record, Set $set) {
                        //                                $record->status = OrderStatus::ACCEPTED;
                        //                                if ($record->save()) {
                        //                                    $set(
                        //                                        "status",
                        //                                        OrderStatus::ACCEPTED->value
                        //                                    );
                        //                                }
                        //                            }),
                        //                        Action::make("Hold")
                        //                            ->label("Hold")
                        //                            ->extraAttributes(["class" => "flex-1"])
                        //                            ->color("warning")
                        //                            ->requiresConfirmation()
                        //                            ->disabled(
                        //                                fn(Order $record) => $record->status ==
                        //                                    OrderStatus::HOLD
                        //                            )
                        //                            ->action(function (Order $record, Set $set) {
                        //                                $record->status = OrderStatus::HOLD;
                        //                                if ($record->save()) {
                        //                                    $set("status", OrderStatus::HOLD->value);
                        //                                }
                        //                            }),
                        //                        Action::make("Decline")
                        //                            ->label(__("dashboard.decline"))
                        //                            ->extraAttributes(["class" => "flex-1"])
                        //                            ->color("danger")
                        //                            ->requiresConfirmation()
                        //                            ->disabled(
                        //                                fn(Order $record) => $record->status ==
                        //                                    OrderStatus::DECLINED
                        //                            )
                        //                            ->action(function (Order $record, Set $set) {
                        //                                $record->status = OrderStatus::DECLINED;
                        //                                if ($record->save()) {
                        //                                    $set(
                        //                                        "status",
                        //                                        OrderStatus::DECLINED->value
                        //                                    );
                        //                                }
                        //                            }),
                    ]),
            ]),
            TableRepeater::make("items")
                ->headers([
                    Header::make(__("store.Name"))->width("150px"),
                    Header::make("Unit Price")->width("150px"),
                    Header::make(__("store.Subtotal"))->width("150px"),
                    Header::make(__("dashboard.Quantity"))->width("150px"),
                ])
                ->relationship(
                    "items",
                    fn($query) => $query->with("purchasable")
                )
                ->deletable(false)
                ->orderColumn(false)
                ->disabled()
                ->addable(false)
                ->schema([
                    TextInput::make("purchasable.name")
                        ->formatStateUsing(
                            fn($state, $record) => $record->purchasable
                                ?->name ?? "-"
                        ) // Add formatting
                        ->disabled(),
                    MoneyInput::make("unit_price")->disabled(),
                    MoneyInput::make("subtotal")->disabled(),
                    TextInput::make("quantity")->disabled(), //TODO: in the future makes quantity editable
                ])
                ->columnSpan("full"),
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
            "index" => Pages\ListOrders::route("/"),
            "create" => Pages\CreateOrder::route("/create"),
            //            "view" => Pages\ViewOrder::route("/{record}"),
            "edit" => Pages\EditOrder::route("/{record}/edit"),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __("store.Orders");
    }

    public static function getModelLabel(): string
    {
        return __("store.Order");
    }

    public static function getPluralModelLabel(): string
    {
        return __("store.Orders");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("store.Shop");
    }

    public static function getLabel(): ?string
    {
        return __("store.Order");
    }

    public static function getPluralLabel(): ?string
    {
        return __("store.orders");
    }
}
