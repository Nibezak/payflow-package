<?php

namespace Payflow\Admin\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Payflow\Admin\Filament\Resources\ProductTypeResource\Pages;
use Payflow\Admin\Support\Forms\Components\AttributeSelector;
use Payflow\Admin\Support\Resources\BaseResource;
use Payflow\Models\Contracts\ProductType;
use Payflow\Models\Product;
use Payflow\Models\ProductVariant;

class ProductTypeResource extends BaseResource
{
    protected static ?string $permission = 'catalog:manage-products';

    protected static ?string $model = ProductType::class;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';  // TODO: remove me in Filament 3.1

    protected static ?int $navigationSort = 2;

    public static function getLabel(): string
    {
        return __('payflowpanel::producttype.label');
    }

    public static function getPluralLabel(): string
    {
        return __('payflowpanel::producttype.plural_label');
    }

    public static function getNavigationParentItem(): ?string
    {
        return __('payflowpanel::product.plural_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('payflowpanel::global.sections.catalog');
    }

    public static function getDefaultForm(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema(
                    static::getMainFormComponents()
                ),
                Forms\Components\Tabs::make('Attributes')->tabs([
                    Forms\Components\Tabs\Tab::make(__('payflowpanel::producttype.tabs.product_attributes.label'))
                        ->schema([
                            AttributeSelector::make('mappedAttributes')
                                ->withType(Product::morphName())
                                ->relationship(name: 'mappedAttributes')
                                ->label('')
                                ->columnSpan(2),
                        ]),
                    Forms\Components\Tabs\Tab::make(__('payflowpanel::producttype.tabs.variant_attributes.label'))
                        ->schema([
                            AttributeSelector::make('mappedAttributes')
                                ->withType(ProductVariant::morphName())
                                ->relationship(name: 'mappedAttributes')
                                ->label('')
                                ->columnSpan(2),
                        ])->visible(
                            config('payflow.panel.enable_variants', true)
                        ),

                ])->columnSpan(2),
            ]);
    }

    protected static function getMainFormComponents(): array
    {
        return [
            static::getNameFormComponent(),
        ];
    }

    protected static function getNameFormComponent(): Component
    {
        return Forms\Components\TextInput::make('name')
            ->label(__('payflowpanel::producttype.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    public static function getDefaultTable(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label(__('payflowpanel::producttype.table.name.label')),
            Tables\Columns\TextColumn::make('products_count')
                ->counts('products')
                ->formatStateUsing(
                    fn ($state) => number_format($state, 0)
                )
                ->label(__('payflowpanel::producttype.table.products_count.label')),
            Tables\Columns\TextColumn::make('product_attributes_count')
                ->counts('productAttributes')
                ->formatStateUsing(
                    fn ($state) => number_format($state, 0)
                )
                ->label(__('payflowpanel::producttype.table.product_attributes_count.label')),
            Tables\Columns\TextColumn::make('variant_attributes_count')
                ->counts('variantAttributes')
                ->formatStateUsing(
                    fn ($state) => number_format($state, 0)
                )
                ->label(__('payflowpanel::producttype.table.variant_attributes_count.label'))
                ->visible(
                    config('payflow.panel.enable_variants', true)
                ),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getDefaultPages(): array
    {
        return [
            'index' => Pages\ListProductTypes::route('/'),
            'create' => Pages\CreateProductType::route('/create'),
            'edit' => Pages\EditProductType::route('/{record}/edit'),
        ];
    }
}
