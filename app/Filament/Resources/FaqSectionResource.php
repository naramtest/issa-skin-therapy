<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqSectionResource\Pages;
use App\Models\FaqSection;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FaqSectionResource extends Resource
{
    use Translatable;

    protected static ?string $model = FaqSection::class;
    protected static ?string $navigationIcon = "heroicon-o-question-mark-circle";
    protected static ?string $navigationGroup = "Content";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->columnSpan(2)
                    ->schema([
                        Forms\Components\Section::make("FAQ Section")->schema([
                            Forms\Components\TextInput::make("title")
                                ->translateLabel()
                                ->required()
                                ->columnSpan(1),
                            Forms\Components\Textarea::make("description")
                                ->translateLabel()
                                ->columnSpan(1),
                        ]),
                        Forms\Components\Section::make("FAQs")->schema([
                            Forms\Components\Repeater::make("faqs")
                                ->hiddenLabel()
                                ->relationship()
                                ->schema([
                                    Forms\Components\TextInput::make("question")
                                        ->hiddenLabel()
                                        ->placeholder("Question")
                                        ->required()
                                        ->columnSpan("full"),
                                    Forms\Components\Textarea::make("answer")
                                        ->hiddenLabel()
                                        ->placeholder("Answer")
                                        ->required()
                                        ->autosize()
                                        ->columnSpan("full"),
                                ])
                                ->orderColumn("sort_order")
                                ->defaultItems(0)
                                ->reorderable()
                                ->cloneable()
                                ->collapsible(),
                        ]),
                    ]),
                Forms\Components\Group::make()
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Section::make()->schema([
                            Forms\Components\Toggle::make("is_active")
                                ->label("Active")
                                ->default(true)
                                ->columnSpan(1),
                            Forms\Components\Toggle::make("is_product_section")
                                ->label("Product Section")
                                ->default(false)
                                ->columnSpan(1)
                                ->disabled(
                                    fn(?Model $record) => $record &&
                                        !$record->is_product_section &&
                                        FaqSection::where(
                                            "is_product_section",
                                            true
                                        )->exists()
                                )
                                ->helperText(
                                    "Only one product FAQ section can exist"
                                ),
                        ]),
                    ]),
            ])
            ->columns(3);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("title")
                    ->translateLabel()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make("is_active")
                    ->label("Active")
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make("is_product_section")
                    ->label("Product Section")
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make("sort_order")->sortable(),
                Tables\Columns\TextColumn::make("faqs_count")
                    ->label("FAQs")
                    ->counts("faqs")
                    ->sortable(),
                Tables\Columns\TextColumn::make("updated_at")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make("is_active")
                    ->label("Active")
                    ->boolean()
                    ->trueLabel("Active")
                    ->falseLabel("Inactive")
                    ->placeholder("All"),
                Tables\Filters\TernaryFilter::make("is_product_section")
                    ->label("Product Section")
                    ->boolean()
                    ->trueLabel("Yes")
                    ->falseLabel("No")
                    ->placeholder("All"),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort("sort_order");
    }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListFaqSections::route("/"),
            "create" => Pages\CreateFaqSection::route("/create"),
            "edit" => Pages\EditFaqSection::route("/{record}/edit"),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount("faqs");
    }
}
