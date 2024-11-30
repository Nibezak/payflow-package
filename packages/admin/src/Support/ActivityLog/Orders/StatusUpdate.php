<?php

namespace Payflow\Admin\Support\ActivityLog\Orders;

use Payflow\Admin\Support\ActivityLog\AbstractRender;
use Payflow\Admin\Support\OrderStatus;
use Spatie\Activitylog\Models\Activity;

class StatusUpdate extends AbstractRender
{
    public function getEvent(): string
    {
        return 'status-update';
    }

    public function render(Activity $log)
    {
        $previousStatus = $log->getExtraProperty('previous');
        $newStatus = $log->getExtraProperty('new');

        return view('payflowpanel::partials.orders.activity.status-update', [
            'log' => $log,
            'previousStatus' => $previousStatus,
            'newStatus' => $newStatus,
            'previousStatusColor' => OrderStatus::getColor($previousStatus),
            'previousStatusLabel' => OrderStatus::getLabel($previousStatus),
            'newStatusColor' => OrderStatus::getColor($newStatus),
            'newStatusLabel' => OrderStatus::getLabel($newStatus),
        ]);
    }
}
