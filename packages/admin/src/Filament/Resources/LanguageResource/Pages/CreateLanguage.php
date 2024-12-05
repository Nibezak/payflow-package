<?php

namespace Payflow\Admin\Filament\Resources\LanguageResource\Pages;

use Payflow\Admin\Filament\Resources\LanguageResource;
use Payflow\Admin\Support\Pages\BaseCreateRecord;

class CreateLanguage extends BaseCreateRecord
{
    protected static string $resource = LanguageResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
