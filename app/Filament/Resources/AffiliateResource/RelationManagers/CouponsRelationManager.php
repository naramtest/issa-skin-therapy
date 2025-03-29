<?php

namespace App\Filament\Resources\AffiliateResource\RelationManagers;

use App\Helpers\Filament\Coupon\CouponForm;
use App\Helpers\Filament\Coupon\CouponTable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CouponsRelationManager extends RelationManager
{
    protected static string $relationship = "coupons";

    public function form(Form $form): Form
    {
        return CouponForm::make($form, [
            Forms\Components\TextInput::make("commission_rate")
                ->label(__("dashboard.Commission Rate (%)"))
                ->required()
                ->numeric()
                ->step(0.01)
                ->prefix("%")
                ->minValue(0)
                ->maxValue(100)
                ->default(10),
        ]);
    }

    public function table(Table $table): Table
    {
        return CouponTable::make($table, [
            Tables\Columns\TextColumn::make("commission_rate")
                ->label(__("dashboard.Commission Rate"))
                ->formatStateUsing(fn($state) => $state . "%")
                ->sortable(),
        ])
            ->recordTitleAttribute("code")
            ->headerActions([Tables\Actions\CreateAction::make()]);
    }
}
