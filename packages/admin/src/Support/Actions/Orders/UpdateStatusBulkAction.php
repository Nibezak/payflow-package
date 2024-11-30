<?php

namespace Payflow\Admin\Support\Actions\Orders;

use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Payflow\Admin\Support\Actions\Traits\UpdatesOrderStatus;
use Payflow\Facades\DB;

class UpdateStatusBulkAction extends BulkAction
{
    use UpdatesOrderStatus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(
            __('payflowpanel::actions.orders.update_status.label')
        );

        $this->modalWidth(MaxWidth::TwoExtraLarge);

        $this->form([
            static::getStatusSelectInput(),
            static::getMailersCheckboxInput(),
            static::getAdditionalContentInput(),
            static::getAdditionalEmailInput(),
        ]);

        $this->action(
            function (Collection $records, array $data) {
                DB::beginTransaction();
                foreach ($records as $record) {
                    $this->updateStatus($record, $data);
                }
                DB::commit();
            }
        );
    }
}
