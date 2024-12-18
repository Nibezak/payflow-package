<?php

namespace Payflow\Admin\Support\Facades;

use Illuminate\Support\Facades\Facade;

class ActivityLog extends Facade
{
    /**
     * Return the facade class reference.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'payflow-activity-log';
    }
}
