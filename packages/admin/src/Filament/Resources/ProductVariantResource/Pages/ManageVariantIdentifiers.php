<?php

namespace Payflow\Admin\Filament\Resources\ProductVariantResource\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Payflow\Admin\Filament\Resources\ProductResource;
use Payflow\Admin\Filament\Resources\ProductVariantResource;
use Payflow\Admin\Support\Pages\BaseEditRecord;

class ManageVariantIdentifiers extends BaseEditRecord
{
    protected static string $resource = ProductVariantResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('payflowpanel::productvariant.pages.identifiers.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('payflowpanel::productvariant.pages.identifiers.title');
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
            ProductVariantResource::getUrl('inventory', [
                'record' => $this->getRecord(),
            ]) => $this->getTitle(),
        ];
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::product-identifiers');
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                ProductVariantResource::getSkuFormComponent()
                    ->live()->unique(
                        table: fn () => $this->getRecord()->getTable(),
                        ignorable: $this->getRecord(),
                        ignoreRecord: true,
                    ),
                ProductVariantResource::getGtinFormComponent(),
                ProductVariantResource::getMpnFormComponent(),
                ProductVariantResource::getEanFormComponent(),
            ])->columns(1),
        ]);
    }

    protected function getDefaultHeaderActions(): array
    {
        return [
            ProductVariantResource::getVariantSwitcherWidget(
                $this->getRecord()
            ),
        ];
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
