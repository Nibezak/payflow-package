<?php

namespace Payflow\Admin\Filament\Resources\ProductOptionResource\Pages;

use Payflow\Admin\Filament\Resources\ProductOptionResource;
use Payflow\Admin\Support\Pages\BaseCreateRecord;

class CreateProductOption extends BaseCreateRecord
{
    protected static string $resource = ProductOptionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['shared'] = true;

        return $data;
    }
}
