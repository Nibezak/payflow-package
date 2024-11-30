<?php

namespace Payflow\Admin\Filament\Resources\CurrencyResource\Pages;

use Payflow\Admin\Filament\Resources\CurrencyResource;
use Payflow\Admin\Support\Pages\BaseCreateRecord;

class CreateCurrency extends BaseCreateRecord
{
    protected static string $resource = CurrencyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
