<?php

namespace Payflow\Admin\Filament\Resources\ProductTypeResource\Pages;

use Payflow\Admin\Filament\Resources\ProductTypeResource;
use Payflow\Admin\Support\Pages\BaseCreateRecord;

class CreateProductType extends BaseCreateRecord
{
    protected static string $resource = ProductTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
