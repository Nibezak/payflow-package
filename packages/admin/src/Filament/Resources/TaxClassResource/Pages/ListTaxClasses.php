<?php

namespace Payflow\Admin\Filament\Resources\TaxClassResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\TaxClassResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListTaxClasses extends BaseListRecords
{
    protected static string $resource = TaxClassResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
