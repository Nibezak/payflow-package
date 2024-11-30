<?php

namespace Payflow\Shipping\Filament\Resources\ShippingMethodResource\Pages;

use Filament\Actions;
use Payflow\Admin\Support\Pages\BaseEditRecord;
use Payflow\Shipping\Filament\Resources\ShippingMethodResource;

class EditShippingMethod extends BaseEditRecord
{
    protected static string $resource = ShippingMethodResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
