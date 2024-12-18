<?php

namespace Payflow\Admin\Filament\Resources\ChannelResource\Pages;

use Payflow\Admin\Filament\Resources\ChannelResource;
use Payflow\Admin\Support\Pages\BaseCreateRecord;

class CreateChannel extends BaseCreateRecord
{
    protected static string $resource = ChannelResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
