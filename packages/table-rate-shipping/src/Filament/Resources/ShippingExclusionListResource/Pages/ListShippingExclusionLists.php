<?php

namespace Payflow\Shipping\Filament\Resources\ShippingExclusionListResource\Pages;

use Filament\Actions;
use Payflow\Admin\Support\Pages\BaseListRecords;
use Payflow\Shipping\Filament\Resources\ShippingExclusionListResource;

class ListShippingExclusionLists extends BaseListRecords
{
    protected static string $resource = ShippingExclusionListResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->form([
                ShippingExclusionListResource::getNameFormComponent(),
            ]),
        ];
    }
}
