<?php

namespace App\Filament\Resources;

use App\Enums\CategoryType;
use App\Filament\Resources\ProductCategoryResource\Pages\ManageProductCategories;
use App\Models\Category;
use App\Services\Filament\Component\CategoryMoveAndDeleteButton;
use App\Services\Filament\Component\CustomNameSlugField;
use Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
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
            ->columns([
                TextColumn::make("order")
                    ->label(__("dashboard.Order"))
                    ->grow(false)
                    ->sortable()
                    ->grow(false),
                TextColumn::make("name")
                    ->label(__("store.Name"))
                    ->sortable()
                    ->searchable(),
                IconColumn::make("is_visible")
                    ->label(__("dashboard.Published"))
                    ->boolean(),
                TextColumn::make("created_at")
                    ->label(__("dashboard.Created At"))
                    ->date("M j, Y")
                    ->sortable(),
            ])
            ->defaultSort("order")
            ->reorderable("order")
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->hidden(
                    fn(Category $record) => !Auth::user()->can(
                        "delete",
                        $record
                    ) or $record->products()->exists()
                ),

                CategoryMoveAndDeleteButton::make(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->where("type", CategoryType::PRODUCT);
            });
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Toggle::make("is_visible")->default(true)->columnSpan(2),
            CustomNameSlugField::getCustomTitleField(
                label: __("store.Name"),
                fieldName: "name"
            ),
            CustomNameSlugField::getCustomSlugField(),
            Textarea::make("description")
                ->label(__("dashboard.Description"))
                ->rows(3)
                ->maxLength(160)
                ->columnSpan(2),
            Hidden::make("type")->default(CategoryType::PRODUCT),
        ]);
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
