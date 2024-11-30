<?php

namespace Payflow\Shipping\Filament\Resources\ShippingMethodResource\Pages;

use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Support\Facades\FilamentIcon;
use Payflow\Admin\Filament\Resources\ProductResource\RelationManagers\CustomerGroupRelationManager;
use Payflow\Admin\Support\Pages\BaseManageRelatedRecords;
use Payflow\Shipping\Filament\Resources\ShippingMethodResource;

class ManageShippingMethodAvailability extends BaseManageRelatedRecords
{
    protected static string $resource = ShippingMethodResource::class;

    protected static string $relationship = 'customerGroups';

    public function getTitle(): string
    {

        return __('payflowpanel.shipping::shippingmethod.pages.availability.label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::availability');
    }

    public static function getNavigationLabel(): string
    {
        return __('payflowpanel.shipping::shippingmethod.pages.availability.label');
    }

    public function getRelationManagers(): array
    {
        return [
            RelationGroup::make('Availability', [
                CustomerGroupRelationManager::make([
                    'description' => __('payflowpanel.shipping::relationmanagers.shipping_methods.customer_groups.description'),
                ]),
            ]),
        ];
    }
}
