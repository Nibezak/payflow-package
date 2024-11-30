<?php

namespace Payflow\Admin\Filament\Resources\CustomerGroupResource\Pages;

use Payflow\Admin\Filament\Resources\CustomerGroupResource;
use Payflow\Admin\Support\Pages\BaseCreateRecord;

class CreateCustomerGroup extends BaseCreateRecord
{
    protected static string $resource = CustomerGroupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
