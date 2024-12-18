<?php

namespace Payflow\Admin\Filament\Resources\CollectionResource\Pages;

use Payflow\Admin\Filament\Resources\CollectionResource;
use Payflow\Admin\Support\Resources\Pages\ManageUrlsRelatedRecords;
use Payflow\Models\Collection;

class ManageCollectionUrls extends ManageUrlsRelatedRecords
{
    protected static string $resource = CollectionResource::class;

    protected static string $model = Collection::class;

    public function getBreadcrumbs(): array
    {
        $crumbs = static::getResource()::getCollectionBreadcrumbs($this->getRecord());

        $crumbs[] = $this->getBreadcrumb();

        return $crumbs;
    }
}
