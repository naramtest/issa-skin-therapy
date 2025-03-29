<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Helpers\Filament\Coupon\CouponForm;
use App\Helpers\Filament\Coupon\CouponTable;
use App\Models\Coupon;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class CouponResource extends Resource
{
    //   TODO: translate and organize
    //    TODO: add 1- Fixed Product Discount
    // (3 items in cart 20$ each if the discount was 10$ the total discount is 30$)
    //    TODO: add products and category restriction
    //    TODO: add a button to auto generate Coupon
    //    TODO: add way to show the orders that coupon is used in (CouponUsage modal)
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = "gmdi-local-offer-o";

    protected static ?int $navigationSort = 1;

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return CouponTable::make($table);
    }

    public static function form(Form $form): Form
    {
        return CouponForm::make($form);
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
            "index" => Pages\ListCoupons::route("/"),
            "create" => Pages\CreateCoupon::route("/create"),
            "edit" => Pages\EditCoupon::route("/{record}/edit"),
        ];
    }

    public static function getLabel(): ?string
    {
        return __("store.Coupon");
    }

    public static function getModelLabel(): string
    {
        return __("store.Coupon");
    }

    public static function getNavigationLabel(): string
    {
        return __("store.Coupons");
    }

    public static function getPluralLabel(): ?string
    {
        return __("store.Coupons");
    }

    public static function getPluralModelLabel(): string
    {
        return __("store.Coupons");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("store.Marketing");
    }
}
