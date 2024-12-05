<?php

namespace Payflow\Admin\Filament\Resources\ProductResource\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Payflow\Admin\Filament\Resources\ProductResource;
use Payflow\Admin\Support\Pages\BaseEditRecord;
use Payflow\Models\ProductVariant;

class ManageProductIdentifiers extends BaseEditRecord
{
    protected static string $resource = ProductResource::class;

    public ?string $sku = null;

    public ?string $gtin = null;

    public ?string $mpn = null;

    public ?string $ean = null;

    public static function booted()
{
    static::creating(function ($product) {
        $product->payflow_user_id = auth()->user()->id;
    });
}


    public function getTitle(): string|Htmlable
    {
        return __('payflowpanel::product.pages.identifiers.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('payflowpanel::product.pages.identifiers.label');
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return $parameters['record']->variants()->count() == 1;
    }

    public function getBreadcrumb(): string
    {
        return __('payflowpanel::product.pages.identifiers.label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::product-identifiers');
    }

    protected function getDefaultHeaderActions(): array
    {
        return [];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $variant = $this->getVariant();

        $this->sku = $variant->sku;
        $this->gtin = $variant->gtin;
        $this->mpn = $variant->mpn;
        $this->ean = $variant->ean;

    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $variant = $this->getVariant();

        $variant->update($data);

        return $record;
    }

    protected function getVariant(): ProductVariant
    {
        return $this->getRecord()->variants()->first();
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }



    public function getRelationManagers(): array
    {
        return [];
    }
}
