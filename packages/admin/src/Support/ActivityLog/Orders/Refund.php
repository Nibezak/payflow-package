<?php

namespace Payflow\Admin\Support\ActivityLog\Orders;

use Payflow\Admin\Support\ActivityLog\AbstractRender;
use Spatie\Activitylog\Models\Activity;

class Refund extends AbstractRender
{
    public function getEvent(): string
    {
        return 'refund';
    }

    public function render(Activity $log)
    {
        return view('payflowpanel::partials.orders.activity.refund', [
            'log' => $log,
        ]);
    }
}
