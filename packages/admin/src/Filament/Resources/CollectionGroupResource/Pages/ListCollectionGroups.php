<?php

namespace Payflow\Admin\Filament\Resources\CollectionGroupResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\CollectionGroupResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListCollectionGroups extends BaseListRecords
{
    protected static string $resource = CollectionGroupResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
