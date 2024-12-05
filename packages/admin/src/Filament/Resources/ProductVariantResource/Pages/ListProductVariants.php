<?php

namespace Payflow\Admin\Filament\Resources\ProductVariantResource\Pages;

use Payflow\Admin\Filament\Resources\ProductVariantResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListProductVariants extends BaseListRecords
{
    protected static string $resource = ProductVariantResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [];
    }

    public static function createActionFormInputs(): array
    {
        return [];
    }

    public function getDefaultTabs(): array
    {
        return [];
    }
}
