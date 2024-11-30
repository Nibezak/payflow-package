<?php

namespace Payflow\Admin\Filament\Resources\ProductResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Payflow\Admin\Events\ProductAssociationsUpdated;
use Payflow\Admin\Filament\Resources\ProductResource;
use Payflow\Admin\Support\Pages\BaseManageRelatedRecords;
use Payflow\Models\Product;
use Payflow\Models\ProductAssociation;

class ManageProductAssociations extends BaseManageRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $relationship = 'associations';

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::product-associations');
    }

    public function getTitle(): string
    {
        return __('payflowpanel::product.pages.associations.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('payflowpanel::product.pages.associations.label');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_target_id')
                    ->label('Product')
                    ->required()
                    ->searchable(true)
                    ->getSearchResultsUsing(static function (Forms\Components\Select $component, string $search): array {
                        return get_search_builder(Product::class, $search)
                            ->get()
                            ->mapWithKeys(fn (Product $record): array => [$record->getKey() => $record->translateAttribute('name')])
                            ->all();
                    }),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        ProductAssociation::ALTERNATE => 'Alternate',
                        ProductAssociation::CROSS_SELL => 'Cross-Sell',
                        ProductAssociation::UP_SELL => 'Upsell',
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->inverseRelationship('parent')
            ->columns([
                Tables\Columns\TextColumn::make('target')
                    ->formatStateUsing(fn (ProductAssociation $record): string => $record->target->translateAttribute('name'))
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column, ProductAssociation $record): ?string {
                        $state = $column->getState();

                        if (strlen($record->target->translateAttribute('name')) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column contents exceeds the length limit.
                        return $record->target->translateAttribute('name');
                    })
                    ->label(__('payflowpanel::product.table.name.label')),
                Tables\Columns\TextColumn::make('target.variants.sku')
                    ->label('SKU'),
                Tables\Columns\TextColumn::make('type'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->after(
                    fn () => ProductAssociationsUpdated::dispatch(
                        $this->getOwnerRecord()
                    )
                ),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()->after(
                    fn () => ProductAssociationsUpdated::dispatch(
                        $this->getOwnerRecord()
                    )
                ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->after(
                        fn () => ProductAssociationsUpdated::dispatch(
                            $this->getOwnerRecord()
                        )
                    ),
                ]),
            ]);
    }
}
