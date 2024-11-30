<?php

namespace Payflow\Admin\Filament\Resources\TagResource\Pages;

use Payflow\Admin\Filament\Resources\TagResource;
use Payflow\Admin\Support\Pages\BaseCreateRecord;

class CreateTag extends BaseCreateRecord
{
    protected static string $resource = TagResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
