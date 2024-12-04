<?php

namespace Payflow\Admin\Filament\Resources\ProductResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Payflow\Admin\Filament\Resources\ProductResource;
use Payflow\Admin\Filament\Resources\ProductResource\RelationManagers\CustomerGroupPricingRelationManager;
use Payflow\Admin\Support\Concerns\Products\ManagesProductPricing;
use Payflow\Admin\Support\Pages\BaseEditRecord;
use Payflow\Admin\Support\RelationManagers\PriceRelationManager;
use Payflow\Models\Currency;
use Payflow\Models\Price;

class ManageProductPricing extends BaseEditRecord
{
    use ManagesProductPricing;

    protected static string $resource = ProductResource::class;

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::product-pricing');
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return $parameters['record']->variants()->count() == 1;
    }

    public function getOwnerRecord(): Model
    {
        return $this->getRecord()->variants()->first();
    }



    public function getRelationManagers(): array
    {
        return [
            CustomerGroupPricingRelationManager::make([
                'ownerRecord' => $this->getOwnerRecord(),
            ]),
            PriceRelationManager::make([
                'ownerRecord' => $this->getOwnerRecord(),
            ]),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return __('payflowpanel::relationmanagers.pricing.title');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->modifyQueryUsing(
                fn ($query) => $query->orderBy('min_quantity', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('price')
                    ->label(
                        __('payflowpanel::relationmanagers.pricing.table.price.label')
                    )->formatStateUsing(
                        fn ($state) => $state->formatted,
                    ),
                Tables\Columns\TextColumn::make('currency.code')->label(
                    __('payflowpanel::relationmanagers.pricing.table.currency.label')
                ),
                Tables\Columns\TextColumn::make('min_quantity')->label(
                    __('payflowpanel::relationmanagers.pricing.table.min_quantity.label')
                ),
                Tables\Columns\TextColumn::make('customerGroup.name')->label(
                    __('payflowpanel::relationmanagers.pricing.table.customer_group.label')
                ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('currency')
                    ->relationship(name: 'currency', titleAttribute: 'name')
                    ->preload(),
                Tables\Filters\SelectFilter::make('min_quantity')->options(
                    Price::where('priceable_id', $this->getOwnerRecord()->id)
                        ->where('priceable_type', $this->getOwnerRecord()->getMorphClass())
                        ->get()
                        ->pluck('min_quantity', 'min_quantity')
                ),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->mutateFormDataUsing(function (array $data) {
                    $currencyModel = Currency::find($data['currency_id']);

                    $data['price'] = (int) ($data['price'] * $currencyModel->factor);

                    return $data;
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->mutateFormDataUsing(function (array $data): array {
                    $currencyModel = Currency::find($data['currency_id']);

                    $data['price'] = (int) ($data['price'] * $currencyModel->factor);

                    return $data;
                }),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
