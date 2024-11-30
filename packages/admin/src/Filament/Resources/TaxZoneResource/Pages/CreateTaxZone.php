<?php

namespace Payflow\Admin\Filament\Resources\TaxZoneResource\Pages;

use Payflow\Admin\Filament\Resources\TaxZoneResource;
use Payflow\Admin\Support\Pages\BaseCreateRecord;

class CreateTaxZone extends BaseCreateRecord
{
    protected static string $resource = TaxZoneResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
