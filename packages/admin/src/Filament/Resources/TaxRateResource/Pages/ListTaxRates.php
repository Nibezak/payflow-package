<?php

namespace Payflow\Admin\Filament\Resources\TaxRateResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\TaxRateResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListTaxRates extends BaseListRecords
{
    protected static string $resource = TaxRateResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
