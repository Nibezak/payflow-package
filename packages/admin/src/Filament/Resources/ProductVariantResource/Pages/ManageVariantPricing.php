<?php

namespace Payflow\Admin\Filament\Resources\ProductVariantResource\Pages;

use Filament\Actions\Action;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;
use Payflow\Admin\Filament\Resources\ProductResource;
use Payflow\Admin\Filament\Resources\ProductResource\RelationManagers\CustomerGroupPricingRelationManager;
use Payflow\Admin\Filament\Resources\ProductVariantResource;
use Payflow\Admin\Support\Concerns\Products\ManagesProductPricing;
use Payflow\Admin\Support\Pages\BaseEditRecord;
use Payflow\Admin\Support\RelationManagers\PriceRelationManager;

class ManageVariantPricing extends BaseEditRecord
{
    use ManagesProductPricing;

    protected static string $resource = ProductVariantResource::class;

    public function getOwnerRecord()
    {
        return $this->getRecord();
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::product-pricing');
    }

    protected function getHeaderActions(): array
    {
        return [
            ProductVariantResource::getVariantSwitcherWidget(
                $this->getRecord()
            ),
        ];
    }

    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()->url(function (Model $record) {
            return ProductResource::getUrl('variants', [
                'record' => $record->product,
            ]);
        });
    }

    public function getBreadcrumbs(): array
    {
        return [
            ...ProductVariantResource::getBaseBreadcrumbs(
                $this->getRecord()
            ),
            ProductVariantResource::getUrl('pricing', [
                'record' => $this->getRecord(),
            ]) => $this->getTitle(),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            CustomerGroupPricingRelationManager::class,
            PriceRelationManager::class,
        ];
    }
}
