<?php

namespace Payflow\Admin\Support\ActivityLog\Orders;

use Payflow\Admin\Support\ActivityLog\AbstractRender;
use Spatie\Activitylog\Models\Activity;

class Intent extends AbstractRender
{
    public function getEvent(): string
    {
        return 'intent';
    }

    public function render(Activity $log)
    {
        return view('payflowpanel::partials.orders.activity.intent', [
            'log' => $log,
        ]);
    }
}
