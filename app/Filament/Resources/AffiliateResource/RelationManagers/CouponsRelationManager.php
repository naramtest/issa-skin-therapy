<?php

namespace App\Filament\Resources\AffiliateResource\RelationManagers;

use App\Enums\CouponType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CouponsRelationManager extends RelationManager
{
    protected static string $relationship = "coupons";

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make("code")
                ->label(__("dashboard.Code"))
                ->required()
                ->maxLength(50)
                ->unique(ignoreRecord: true)
                ->default(fn() => strtoupper(Str::random(8))),
            Forms\Components\Textarea::make("description")
                ->label(__("dashboard.Description"))
                ->maxLength(65535)
                ->columnSpanFull(),
            Forms\Components\Select::make("discount_type")
                ->label(__("dashboard.Discount Type"))
                ->options([
                    CouponType::FIXED->value => CouponType::FIXED->getLabel(),
                    CouponType::PERCENTAGE
                        ->value => CouponType::PERCENTAGE->getLabel(),
                    CouponType::SHIPPING
                        ->value => CouponType::SHIPPING->getLabel(),
                ])
                ->required(),
            Forms\Components\TextInput::make("discount_amount")
                ->label(__("dashboard.Discount Amount"))
                ->required()
                ->numeric()
                ->minValue(0),
            Forms\Components\TextInput::make("minimum_spend")
                ->label(__("dashboard.Minimum Spend"))
                ->numeric()
                ->minValue(0),
            Forms\Components\TextInput::make("maximum_spend")
                ->label(__("dashboard.Maximum Spend"))
                ->numeric()
                ->minValue(0)
                ->gt("minimum_spend"),
            Forms\Components\TextInput::make("usage_limit")
                ->label(__("dashboard.Usage Limit"))
                ->integer()
                ->minValue(0),
            Forms\Components\Toggle::make("includes_free_shipping")
                ->label(__("dashboard.Includes Free Shipping"))
                ->default(false),
            Forms\Components\DateTimePicker::make("starts_at")->label(
                __("dashboard.Start Date")
            ),
            Forms\Components\DateTimePicker::make("expires_at")
                ->label(__("dashboard.Expiry Date"))
                ->after("starts_at"),
            Forms\Components\Toggle::make("is_active")
                ->label(__("dashboard.Active"))
                ->default(true),
            Forms\Components\TextInput::make("commission_rate")
                ->label(__("dashboard.Commission Rate (%)"))
                ->required()
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->default(10),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute("code")
            ->columns([
                Tables\Columns\TextColumn::make("code")
                    ->label(__("dashboard.Code"))
                    ->searchable(),
                Tables\Columns\TextColumn::make("discount_type")
                    ->label(__("dashboard.Discount Type"))
                    ->formatStateUsing(
                        fn(CouponType $state) => $state->getLabel()
                    ),
                Tables\Columns\TextColumn::make("discount_amount")
                    ->label(__("dashboard.Discount Amount"))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make("used_count")
                    ->label(__("dashboard.Used"))
                    ->counts("usage")
                    ->sortable(),
                Tables\Columns\TextColumn::make("commission_rate")
                    ->label(__("dashboard.Commission Rate"))
                    ->formatStateUsing(fn($state) => $state . "%")
                    ->sortable(),
                Tables\Columns\IconColumn::make("is_active")
                    ->label(__("dashboard.Active"))
                    ->boolean(),
                Tables\Columns\TextColumn::make("expires_at")
                    ->label(__("dashboard.Expires"))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make("is_active")
                    ->label(__("dashboard.Status"))
                    ->options([
                        "1" => __("dashboard.Active"),
                        "0" => __("dashboard.Inactive"),
                    ]),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()])
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
}
