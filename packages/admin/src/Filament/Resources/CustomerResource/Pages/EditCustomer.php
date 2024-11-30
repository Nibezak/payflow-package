<?php

namespace Payflow\Admin\Filament\Resources\CustomerResource\Pages;

use Payflow\Admin\Filament\Resources\CustomerResource;
use Payflow\Admin\Support\Pages\BaseEditRecord;

class EditCustomer extends BaseEditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
