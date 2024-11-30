<?php

namespace Payflow\Admin\Filament\Resources\ProductOptionResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\ProductOptionResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListProductOptions extends BaseListRecords
{
    protected static string $resource = ProductOptionResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
