<?php

namespace Payflow\Admin\Support\ActivityLog\Orders;

use Payflow\Admin\Support\ActivityLog\AbstractRender;
use Spatie\Activitylog\Models\Activity;

class EmailNotification extends AbstractRender
{
    public function getEvent(): string
    {
        return 'email-notification';
    }

    public function render(Activity $log)
    {
        return view('payflowpanel::partials.orders.activity.email-notification', [
            'log' => $log,
        ]);
    }
}
