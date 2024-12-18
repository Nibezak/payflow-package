<?php

namespace Payflow\Admin\Filament\Resources\StaffResource\Pages;

use Filament\Actions;
use Filament\Support\Colors\Color;
use Payflow\Admin\Filament\Resources\StaffResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListStaff extends BaseListRecords
{
    protected static string $resource = StaffResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\Action::make('access-control')
                ->label(__('payflowpanel::staff.action.acl.label'))
                ->color(Color::Lime)
                ->url(fn () => StaffResource::getUrl('acl')),
            Actions\CreateAction::make(),
        ];
    }
}
