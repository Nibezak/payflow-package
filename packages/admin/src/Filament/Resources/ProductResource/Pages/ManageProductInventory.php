<?php

namespace Payflow\Admin\Filament\Resources\ProductResource\Pages;

use Filament\Forms\Form;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Payflow\Admin\Filament\Resources\ProductResource;
use Payflow\Admin\Support\Pages\BaseEditRecord;
use Payflow\Models\ProductVariant;

class ManageProductInventory extends BaseEditRecord
{
    protected static string $resource = ProductResource::class;

    public ?string $stock = null;

    public ?string $backorder = null;

    public ?string $purchasable = null;

    public ?int $unit_quantity = 1;

    public ?int $quantity_increment = 1;

    public ?int $min_quantity = 1;

    public static function booted()
{
    static::creating(function ($product) {
        $product->payflow_user_id = auth()->user()->id;
    });
}


    public function getTitle(): string|Htmlable
    {
        return __('payflowpanel::product.pages.inventory.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('payflowpanel::product.pages.inventory.label');
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return $parameters['record']->variants()->count() == 1;
    }

    public function getBreadcrumb(): string
    {
        return __('payflowpanel::product.pages.inventory.label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::product-inventory');
    }

    protected function getDefaultHeaderActions(): array
    {
        return [];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $variant = $this->getVariant();

        $this->stock = $variant->stock;
        $this->backorder = $variant->backorder;
        $this->purchasable = $variant->purchasable;
        $this->unit_quantity = $variant->unit_quantity;
        $this->min_quantity = $variant->min_quantity;
        $this->quantity_increment = $variant->quantity_increment;
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

    public function getDefaultForm(Form $form): Form
    {
        return (new ManageVariantInventory)->form($form)->statePath('');
    }

    public function getRelationManagers(): array
    {
        return [];
    }
}
