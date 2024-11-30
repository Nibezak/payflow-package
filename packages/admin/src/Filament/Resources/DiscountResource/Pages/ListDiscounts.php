<?php

namespace Payflow\Admin\Filament\Resources\DiscountResource\Pages;

use Filament\Actions;
use Filament\Forms;
use Payflow\Admin\Filament\Resources\DiscountResource;
use Payflow\Admin\Support\Pages\BaseListRecords;

class ListDiscounts extends BaseListRecords
{
    protected static string $resource = DiscountResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->form([
                Forms\Components\Group::make([
                    DiscountResource::getNameFormComponent(),
                    DiscountResource::getHandleFormComponent(),
                ])->columns(2),
                Forms\Components\Group::make([
                    DiscountResource::getStartsAtFormComponent(),
                    DiscountResource::getEndsAtFormComponent(),
                ])->columns(2),
                DiscountResource::getDiscountTypeFormComponent(),
            ]),
        ];
    }
}
