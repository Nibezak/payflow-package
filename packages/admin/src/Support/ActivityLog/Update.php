<?php

namespace Payflow\Admin\Support\ActivityLog;

use Spatie\Activitylog\Models\Activity;

class Update extends AbstractRender
{
    public function getEvent(): string
    {
        return 'updated';
    }

    public function render(Activity $log)
    {
        return view('payflowpanel::partials.activity-log.update', [
            'log' => $log,
            'model' => str($log->subject::class)->classBasename()->snake(' ')->ucfirst(),
        ]);
    }
}
