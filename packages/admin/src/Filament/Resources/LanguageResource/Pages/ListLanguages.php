<?php

namespace Payflow\Admin\Filament\Resources\LanguageResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\LanguageResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListLanguages extends BaseListRecords
{
    protected static string $resource = LanguageResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
