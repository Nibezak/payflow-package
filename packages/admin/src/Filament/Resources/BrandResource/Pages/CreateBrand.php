<?php

namespace Payflow\Admin\Filament\Resources\BrandResource\Pages;

use Payflow\Admin\Filament\Resources\BrandResource;
use Payflow\Admin\Support\Pages\BaseCreateRecord;

class CreateBrand extends BaseCreateRecord
{
    protected static string $resource = BrandResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
