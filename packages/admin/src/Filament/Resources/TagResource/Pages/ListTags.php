<?php

namespace Payflow\Admin\Filament\Resources\TagResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\TagResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListTags extends BaseListRecords
{
    protected static string $resource = TagResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
