<?php

namespace Payflow\Admin\Filament\Resources\TagResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\TagResource;
use Payflow\Admin\Support\Pages\BaseEditRecord;

class EditTag extends BaseEditRecord
{
    protected static string $resource = TagResource::class;

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
