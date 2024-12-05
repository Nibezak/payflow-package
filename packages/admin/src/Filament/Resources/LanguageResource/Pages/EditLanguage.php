<?php

namespace Payflow\Admin\Filament\Resources\LanguageResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\LanguageResource;
use Payflow\Admin\Support\Pages\BaseEditRecord;

class EditLanguage extends BaseEditRecord
{
    protected static string $resource = LanguageResource::class;

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
