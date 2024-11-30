<?php

namespace Payflow\Shipping\Filament\Resources\ShippingMethodResource\Pages;

use Filament\Actions;
use Filament\Forms\Components\Group;
use Payflow\Admin\Support\Pages\BaseListRecords;
use Payflow\Models\CustomerGroup;
use Payflow\Shipping\Filament\Resources\ShippingMethodResource;
use Payflow\Shipping\Models\ShippingMethod;

class ListShippingMethod extends BaseListRecords
{
    protected static string $resource = ShippingMethodResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->form([
                ShippingMethodResource::getNameFormComponent(),
                Group::make([
                    ShippingMethodResource::getCodeFormComponent(),
                    ShippingMethodResource::getDriverFormComponent(),
                ])->columns(2),
                ShippingMethodResource::getDescriptionFormComponent(),
            ])->after(function (ShippingMethod $shippingMethod) {
                $customerGroups = CustomerGroup::pluck('id')->mapWithKeys(
                    fn ($id) => [$id => ['visible' => true, 'enabled' => true, 'starts_at' => now()]]
                );
                $shippingMethod->customerGroups()->sync($customerGroups);
            }),
        ];
    }
}
