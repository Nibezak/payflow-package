<?php

namespace Payflow\Admin\Filament\Resources\CollectionResource\Pages;

use Payflow\Admin\Filament\Resources\CollectionResource;
use Payflow\Admin\Support\Resources\Pages\ManageMediasRelatedRecords;

class ManageCollectionMedia extends ManageMediasRelatedRecords
{
    protected static string $resource = CollectionResource::class;

    public function getBreadcrumbs(): array
    {
        $crumbs = static::getResource()::getCollectionBreadcrumbs($this->getRecord());

        $crumbs[] = $this->getBreadcrumb();

        return $crumbs;
    }
}
