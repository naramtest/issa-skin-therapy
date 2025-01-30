<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\Checkout\OrderStatus;
use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    public function getTabs(): array
    {
        $tabs = [
            __("store.All") => Tab::make(),
        ];
        foreach (OrderStatus::cases() as $status) {
            $tabs[$status->getLabel()] = Tab::make()->modifyQueryUsing(
                fn(Builder $query) => $query->where("status", "=", $status)
            );
        }
        return $tabs;
    }

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
