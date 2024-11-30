<?php

namespace Payflow\Admin\Filament\Resources\CurrencyResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\CurrencyResource;
use Payflow\Admin\Support\Pages\BaseEditRecord;

class EditCurrency extends BaseEditRecord
{
    protected static string $resource = CurrencyResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
