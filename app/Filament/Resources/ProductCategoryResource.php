<?php

namespace App\Filament\Resources;

use App\Enums\CategoryType;
use App\Filament\Resources\ProductCategoryResource\Pages\ManageProductCategories;
use App\Helpers\Filament\Category\CategoryForm;
use App\Helpers\Filament\Category\CategoryTable;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductCategoryResource extends Resource
{
    use Translatable;

    protected static ?string $model = Category::class;

    protected static ?string $slug = "product-categories";

    protected static ?string $navigationIcon = "heroicon-o-rectangle-stack";

    public static function table(Table $table): Table
    {
        return $table
            ->columns(CategoryTable::columns())
            ->defaultSort("order")
            ->reorderable("order")
            ->actions(CategoryTable::actions())
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where("type", CategoryType::PRODUCT);
            });
    }

    public static function form(Form $form): Form
    {
        return CategoryForm::make($form, CategoryType::PRODUCT);
    }

    public static function getPages(): array
    {
        return [
            "index" => ManageProductCategories::route("/"),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ["name", "slug"];
    }

    public static function getLabel(): ?string
    {
        return __("dashboard.Category");
    }

    public static function getModelLabel(): string
    {
        return __("dashboard.Category");
    }

    public static function getNavigationLabel(): string
    {
        return __("dashboard.Categories");
    }

    public static function getPluralLabel(): ?string
    {
        return __("dashboard.Categories");
    }

    public static function getPluralModelLabel(): string
    {
        return __("dashboard.Categories");
    }

    public static function getNavigationGroup(): ?string
    {
        return __("store.Shop");
    }

    public static function getNavigationParentItem(): ?string
    {
        return __("store.Products");
    }
}
