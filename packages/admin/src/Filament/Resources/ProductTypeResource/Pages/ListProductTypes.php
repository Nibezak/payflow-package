<?php

namespace Payflow\Admin\Filament\Resources\ProductTypeResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\ProductTypeResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListProductTypes extends BaseListRecords
{
    protected static string $resource = ProductTypeResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
