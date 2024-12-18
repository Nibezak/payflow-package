<?php

namespace Payflow\Admin\Support\ActivityLog;

use Spatie\Activitylog\Models\Activity;

class TagsUpdate extends AbstractRender
{
    public function getEvent(): string
    {
        return 'tags-update';
    }

    public function render(Activity $log)
    {
        return view('payflowpanel::partials.activity-log.tags-update', [
            'log' => $log,
            'added' => $log->getExtraProperty('added'),
            'removed' => $log->getExtraProperty('removed'),
        ]);
    }
}
