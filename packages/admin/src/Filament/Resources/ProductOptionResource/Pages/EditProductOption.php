<?php

namespace Payflow\Admin\Filament\Resources\ProductOptionResource\Pages;

use Filament\Actions;
use Payflow\Admin\Filament\Resources\ProductOptionResource;
use Payflow\Admin\Filament\Resources\ProductOptionResource\RelationManagers;
use Payflow\Admin\Support\Pages\BaseEditRecord;

class EditProductOption extends BaseEditRecord
{
    protected static string $resource = ProductOptionResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getRelationManagers(): array
    {
        return $this->record->shared ? [
            RelationManagers\ValuesRelationManager::class,
        ] : [];
    }
}
