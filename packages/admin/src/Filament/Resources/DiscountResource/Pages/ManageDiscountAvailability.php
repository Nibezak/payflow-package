<?php

namespace Payflow\Admin\Filament\Resources\DiscountResource\Pages;

use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Support\Facades\FilamentIcon;
use Payflow\Admin\Filament\Resources\DiscountResource;
use Payflow\Admin\Filament\Resources\ProductResource\RelationManagers\CustomerGroupRelationManager;
use Payflow\Admin\Support\Pages\BaseManageRelatedRecords;
use Payflow\Admin\Support\RelationManagers\ChannelRelationManager;

class ManageDiscountAvailability extends BaseManageRelatedRecords
{
    protected static string $resource = DiscountResource::class;

    protected static string $relationship = 'channels';

    public function getTitle(): string
    {
        return __('payflowpanel::discount.pages.availability.label');
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::availability');
    }

    public static function getNavigationLabel(): string
    {
        return __('payflowpanel::discount.pages.availability.label');
    }

    public function getRelationManagers(): array
    {
        return [
            RelationGroup::make('Availability', [
                ChannelRelationManager::class,
                CustomerGroupRelationManager::make([
                    'pivots' => [
                        'enabled',
                        'visible',
                    ],
                ]),
            ]),
        ];
    }
}
