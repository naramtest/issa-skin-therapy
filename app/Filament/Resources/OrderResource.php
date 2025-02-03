<?php

namespace App\Filament\Resources;

use App\Enums\Checkout\OrderStatus;
use App\Enums\Checkout\PaymentStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\Partials\Components\DHLAction;
use App\Filament\Resources\OrderResource\Partials\Components\EmailAction;
use App\Filament\Resources\OrderResource\Partials\OrderForm;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = "gmdi-shopping-cart-o";
    protected static ?int $navigationSort = 1;

    /**
     * @throws \Exception
     */
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
                DateRangeFilter::make("created_at")->label(
                    __("dashboard.Created At")
                ),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make("createShipment")
                    ->label("DHL")
                    ->icon("heroicon-o-truck")
                    ->requiresConfirmation()
                    ->hidden(fn(Order $record) => DHLAction::hidden($record))
                    ->action(fn(Order $record) => DHLAction::action($record)),

                ActionGroup::make([
                    Action::make("download")
                        ->label("Action")
                        ->icon("gmdi-download-o")
                        ->url(function (Order $record) {
                            return route("orders.invoice.download", [
                                "order" => $record,
                            ]);
                        }),
                    ActionGroup::make([
                        EmailAction::make(
                            "processing_email",
                            __("store.Processing Email"),
                            OrderStatus::PROCESSING
                        ),
                        EmailAction::make(
                            "completed_email",
                            __("store.Completed Email"),
                            OrderStatus::COMPLETED
                        ),
                        EmailAction::make(
                            "canceled_email",
                            __("store.Canceled Email"),
                            OrderStatus::CANCELLED
                        ),
                    ])->dropdown(false),
                ]),
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
        return OrderForm::newForm($form);
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
        return __("store.Orders");
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()
            ::where("status", "=", OrderStatus::PENDING)
            ->orWhere("status", "=", OrderStatus::PROCESSING)
            ->count();
    }
}
