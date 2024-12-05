<?php

namespace Payflow\Admin\Filament\Resources\AttributeGroupResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\AttributeGroupResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListAttributeGroups extends BaseListRecords
{
    protected static string $resource = AttributeGroupResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
