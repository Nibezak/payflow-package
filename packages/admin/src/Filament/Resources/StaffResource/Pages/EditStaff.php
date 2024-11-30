<?php

namespace Payflow\Admin\Filament\Resources\StaffResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\StaffResource;
use Payflow\Admin\Support\Pages\BaseEditRecord;

class EditStaff extends BaseEditRecord
{
    protected static string $resource = StaffResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
