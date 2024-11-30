<?php

namespace Payflow\Admin\Filament\Resources\ChannelResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\ChannelResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListChannels extends BaseListRecords
{
    protected static string $resource = ChannelResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
