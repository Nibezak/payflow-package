<?php

namespace Payflow\Admin\Support\Actions\Collections;

use Filament\Actions\DeleteAction;
use Payflow\Models\Collection;

class DeleteCollection extends DeleteAction
{
    public function setUp(): void
    {
        parent::setUp();

        $this->record(function (array $arguments) {
            return Collection::find($arguments['id']);
        });

        $this->label(
            __('payflowpanel::actions.collections.delete.label')
        );
    }
}
