<?php

namespace Payflow\Admin\Filament\Resources\CustomerResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\CustomerResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListCustomers extends BaseListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
