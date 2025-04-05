<?php

namespace App\Filament\Widgets;

use App\Enums\Checkout\OrderStatus;
use App\Helpers\Filament\OrderTable;
use App\Models\Order;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;

class LatestOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = "full";

    public static function canView(): bool
    {
        return auth()->check() && auth()->user()->hasRole("affiliate");
    }

    public function table(Table $table): Table
    {
        return OrderTable::make($table, [
            MoneyColumn::make("subtotal_after_coupon")
                ->label(__("store.Subtotal"))
                ->sortable(),
            MoneyColumn::make("commission.commission_amount")
                ->label(__("dashboard.Commission"))
                ->sortable(),
        ])
            ->heading("Latest Orders")
            ->query(function () {
                return Order::query()
                    ->where(function ($query) {
                        return $query
                            ->where("status", OrderStatus::COMPLETED)
                            ->orWhere("status", OrderStatus::PROCESSING);
                    })
                    ->whereHas("commission", function ($query) {
                        $query->whereHas("affiliate", function ($query) {
                            $query->where("user_id", auth()->id());
                        });
                    })
                    ->latest()
                    ->limit(10);
            });
    }
}
