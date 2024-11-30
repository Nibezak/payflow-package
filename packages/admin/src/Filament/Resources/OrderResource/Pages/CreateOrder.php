<?php

namespace Payflow\Admin\Filament\Resources\OrderResource\Pages;

use Payflow\Admin\Filament\Resources\OrderResource;
use Payflow\Admin\Support\Pages\BaseCreateRecord;

class CreateOrder extends BaseCreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
