<?php

namespace Payflow\Shipping\Filament\Resources\ShippingZoneResource\Pages;

use Filament\Actions;
use Payflow\Admin\Support\Pages\BaseListRecords;
use Payflow\Shipping\Filament\Resources\ShippingZoneResource;

class ListShippingZones extends BaseListRecords
{
    protected static string $resource = ShippingZoneResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->form([
                ShippingZoneResource::getNameFormComponent(),
                ShippingZoneResource::getTypeFormComponent(),
            ]),
        ];
    }
}
