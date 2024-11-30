<?php

namespace Payflow\Admin\Filament\Resources\TaxRateResource\Pages;

use Payflow\Admin\Filament\Resources\TaxRateResource;
use Payflow\Admin\Support\Pages\BaseCreateRecord;

class CreateTaxRate extends BaseCreateRecord
{
    protected static string $resource = TaxRateResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
