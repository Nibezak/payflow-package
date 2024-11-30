<?php

namespace Payflow\Admin\Filament\Resources\CollectionResource\Pages;

use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Support\Facades\FilamentIcon;
use Payflow\Admin\Filament\Resources\CollectionResource;
use Payflow\Admin\Filament\Resources\ProductResource\RelationManagers\CustomerGroupRelationManager;
use Payflow\Admin\Support\Pages\BaseManageRelatedRecords;
use Payflow\Admin\Support\RelationManagers\ChannelRelationManager;

class ManageCollectionAvailability extends BaseManageRelatedRecords
{
    protected static string $resource = CollectionResource::class;

    protected static string $relationship = 'channels';

    public function getTitle(): string
    {
        return __('payflowpanel::product.pages.availability.label');
    }

    public function getBreadcrumbs(): array
    {
        $crumbs = static::getResource()::getCollectionBreadcrumbs($this->getRecord());

        $crumbs[] = $this->getBreadcrumb();

        return $crumbs;
    }

    public static function getNavigationIcon(): ?string
    {
        return FilamentIcon::resolve('payflow::availability');
    }

    public static function getNavigationLabel(): string
    {
        return __('payflowpanel::product.pages.availability.label');
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
