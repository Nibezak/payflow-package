<?php

namespace Payflow\Admin\Filament\Resources\ProductResource\Pages;

use Filament\Forms;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Payflow\Admin\Events\ProductCollectionsUpdated;
use Payflow\Admin\Filament\Resources\ProductResource;
use Payflow\Admin\Support\Pages\BaseManageRelatedRecords;
use Payflow\Admin\Support\Tables\Columns\TranslatedTextColumn;
use Payflow\Models\Collection;

class ManageProductCollections extends BaseManageRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $relationship = 'collections';

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::collections');
    }
    public static function booted()
    {
        static::creating(function ($product) {
            $product->payflow_user_id = auth()->user()->id;
        });
    }
    
    public function getTitle(): string
    {
        return __('payflowpanel::product.pages.collections.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('payflowpanel::product.pages.collections.label');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TranslatedTextColumn::make('attribute_data.name')
                    ->attributeData()
                    ->limitedTooltip()
                    ->limit(50)
                    ->label(__('payflowpanel::product.table.name.label')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->recordSelect(
                        function (Forms\Components\Select $select) {
                            return $select->placeholder('Select a collection') // TODO: needs translation
                                ->getSearchResultsUsing(static function (Forms\Components\Select $component, string $search, ManageProductCollections $livewire): array {
                                    $relationModel = $livewire->getRelationship()->getRelated()::class;

                                    return get_search_builder($relationModel, $search)
                                        ->get()
                                        ->mapWithKeys(fn (Collection $record): array => [$record->getKey() => $record->breadcrumb->push($record->translateAttribute('name'))->join(' > ')])
                                        ->all();
                                });
                        }
                    )->after(
                        fn () => ProductCollectionsUpdated::dispatch(
                            $this->getOwnerRecord()
                        )
                    ),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()->after(
                    fn () => ProductCollectionsUpdated::dispatch(
                        $this->getOwnerRecord()
                    )
                ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()->after(
                        fn () => ProductCollectionsUpdated::dispatch(
                            $this->getOwnerRecord()
                        )
                    ),
                ]),
            ]);
    }
}
