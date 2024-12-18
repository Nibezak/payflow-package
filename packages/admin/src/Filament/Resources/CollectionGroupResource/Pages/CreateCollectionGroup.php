<?php

namespace Payflow\Admin\Filament\Resources\CollectionGroupResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Payflow\Admin\Filament\Resources\CollectionGroupResource;

class CreateCollectionGroup extends CreateRecord
{
    protected static string $resource = CollectionGroupResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
