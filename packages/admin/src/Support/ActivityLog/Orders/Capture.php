<?php

namespace Payflow\Admin\Support\ActivityLog\Orders;

use Payflow\Admin\Support\ActivityLog\AbstractRender;
use Spatie\Activitylog\Models\Activity;

class Capture extends AbstractRender
{
    public function getEvent(): string
    {
        return 'capture';
    }

    public function render(Activity $log)
    {
        return view('payflowpanel::partials.orders.activity.capture', [
            'log' => $log,
        ]);
    }
}
