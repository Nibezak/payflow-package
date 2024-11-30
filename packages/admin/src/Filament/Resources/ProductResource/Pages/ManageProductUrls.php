<?php

namespace Payflow\Admin\Filament\Resources\ProductResource\Pages;

use Payflow\Admin\Filament\Resources\ProductResource;
use Payflow\Admin\Support\Resources\Pages\ManageUrlsRelatedRecords;
use Payflow\Models\Product;

class ManageProductUrls extends ManageUrlsRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $model = Product::class;
}
