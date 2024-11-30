<?php

namespace Payflow\Admin\Filament\Resources\AttributeGroupResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Payflow\Admin\Filament\Resources\AttributeGroupResource;
use Payflow\Admin\Support\Pages\BaseEditRecord;

class EditAttributeGroup extends BaseEditRecord
{
    protected static string $resource = AttributeGroupResource::class;

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function ($record, Actions\DeleteAction $action) {
                    if ($record->attributes->count() > 0) {
                        Notification::make()
                            ->warning()
                            ->body(__('payflowpanel::attributegroup.action.delete.notification.error_protected'))
                            ->send();
                        $action->cancel();
                    }
                }),
        ];
    }
}
