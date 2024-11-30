<?php

namespace Payflow\Admin\Filament\Resources\CurrencyResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\CurrencyResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListCurrencies extends BaseListRecords
{
    protected static string $resource = CurrencyResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
