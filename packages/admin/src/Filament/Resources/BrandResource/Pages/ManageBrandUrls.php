<?php

namespace Payflow\Admin\Filament\Resources\BrandResource\Pages;

use Payflow\Admin\Filament\Resources\BrandResource;
use Payflow\Admin\Support\Resources\Pages\ManageUrlsRelatedRecords;
use Payflow\Models\Brand;

class ManageBrandUrls extends ManageUrlsRelatedRecords
{
    protected static string $resource = BrandResource::class;

    protected static string $model = Brand::class;
}
