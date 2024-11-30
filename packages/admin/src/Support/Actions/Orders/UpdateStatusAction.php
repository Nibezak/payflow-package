<?php

namespace Payflow\Admin\Support\Actions\Orders;

use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Payflow\Admin\Support\Actions\Traits\UpdatesOrderStatus;
use Payflow\Models\Order;

class UpdateStatusAction extends Action
{
    use UpdatesOrderStatus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(
            __('payflowpanel::actions.orders.update_status.label')
        );

        $this->modalWidth(MaxWidth::TwoExtraLarge);

        $this->form(
            $this->getFormSteps()
        );

        $this->action(
            fn (Order $record, array $data) => $this->updateStatus($record, $data)
        );
    }
}
