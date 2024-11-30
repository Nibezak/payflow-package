<?php

namespace Payflow\Admin\Filament\Resources\DiscountResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Payflow\Admin\Support\RelationManagers\BaseRelationManager;
use Payflow\Facades\ModelManifest;
use Payflow\Models\Product;
use Payflow\Models\ProductVariant;

class ProductVariantLimitationRelationManager extends BaseRelationManager
{
    protected static bool $isLazy = false;

    protected static string $relationship = 'purchasables';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function getDefaultTable(Table $table): Table
    {

        return $table
            ->heading(
                __('payflowpanel::discount.relationmanagers.productvariants.title')
            )
            ->description(
                __('payflowpanel::discount.relationmanagers.productvariants.description')
            )
            ->paginated(false)
            ->modifyQueryUsing(
                fn ($query) => $query->whereIn('type', ['limitation', 'exclusion'])
                    ->wherePurchasableType(
                        ModelManifest::getMorphMapKey(ProductVariant::class)
                    )
                    ->whereHas('purchasable')
            )
            ->headerActions([
                Tables\Actions\CreateAction::make()->form([
                    Forms\Components\MorphToSelect::make('purchasable')
                        ->searchable(true)
                        ->types([
                            Forms\Components\MorphToSelect\Type::make(ProductVariant::class)
                                ->titleAttribute('sku')
                                ->getSearchResultsUsing(static function (Forms\Components\Select $component, string $search): array {
                                    $products = get_search_builder(Product::class, $search)
                                        ->get();

                                    return ProductVariant::whereIn('product_id', $products->pluck('id'))
                                        ->get()
                                        ->mapWithKeys(fn (ProductVariant $record): array => [$record->getKey() => $record->product->attr('name').' - '.$record->sku])
                                        ->all();
                                }),
                        ]),
                ])->label(
                    __('payflowpanel::discount.relationmanagers.productvariants.actions.attach.label')
                )->mutateFormDataUsing(function (array $data) {
                    $data['type'] = 'limitation';

                    return $data;
                }),
            ])->columns([
                Tables\Columns\TextColumn::make('purchasable')
                    ->formatStateUsing(
                        fn (Model $model) => $model->purchasable->getDescription()
                    )
                    ->label(
                        __('payflowpanel::discount.relationmanagers.productvariants.table.name.label')
                    ),
                Tables\Columns\TextColumn::make('purchasable.sku')
                    ->label(
                        __('payflowpanel::discount.relationmanagers.productvariants.table.sku.label')
                    ),
                Tables\Columns\TextColumn::make('purchasable.values')
                    ->formatStateUsing(function (Model $record) {
                        return $record->purchasable->values->map(
                            fn ($value) => $value->translate('name')
                        )->join(', ');
                    })->label(
                        __('payflowpanel::discount.relationmanagers.productvariants.table.values.label')
                    ),
            ])->actions([
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
