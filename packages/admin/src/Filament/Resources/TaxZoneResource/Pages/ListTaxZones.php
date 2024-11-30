<?php

namespace Payflow\Admin\Filament\Resources\TaxZoneResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\TaxZoneResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListTaxZones extends BaseListRecords
{
    protected static string $resource = TaxZoneResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
