<?php

namespace Payflow\Admin\Filament\Resources\CustomerGroupResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\CustomerGroupResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListCustomerGroups extends BaseListRecords
{
    protected static string $resource = CustomerGroupResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
