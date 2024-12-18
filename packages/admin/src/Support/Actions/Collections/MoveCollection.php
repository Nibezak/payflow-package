<?php

namespace Payflow\Admin\Support\Actions\Collections;

use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Payflow\Facades\DB;
use Payflow\Models\Collection;

class MoveCollection extends Action
{
    public function setUp(): void
    {
        parent::setUp();

        $this->record(function (array $arguments) {
            return Collection::find($arguments['id']);
        });

        $this->action(function (array $arguments, array $data, Model $record): void {
            DB::beginTransaction();

            $target = Collection::find($data['target_id']);

            $record->parent()->associate($target)->save();

            DB::commit();

            $this->success();

        });

        $this->label(
            __('payflowpanel::actions.collections.move.label')
        );
    }
}
