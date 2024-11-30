<?php

namespace Payflow\Admin\Filament\Resources\TaxClassResource\Pages;

use Payflow\Admin\Filament\Resources\TaxClassResource;
use Payflow\Admin\Support\Pages\BaseCreateRecord;

class CreateTaxClass extends BaseCreateRecord
{
    protected static string $resource = TaxClassResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
