<?php

namespace Payflow\Shipping\Filament\Resources\ShippingZoneResource\Pages;

use Filament\Actions;
use Payflow\Admin\Support\Pages\BaseEditRecord;
use Payflow\Shipping\Filament\Resources\ShippingZoneResource;

class EditShippingZone extends BaseEditRecord
{
    protected static string $resource = ShippingZoneResource::class;

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
