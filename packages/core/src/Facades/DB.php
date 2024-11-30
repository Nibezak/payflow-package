<?php

namespace Payflow\Facades;

use Illuminate\Support\Facades\DB as DBFacade;

class DB extends DBFacade
{
    /**
     * Get the registered DatabaseManger class.
     *
     * @return \Payflow\Managers\DatabaseManager
     */
    public static function connection()
    {
        // return custom connection
        return parent::connection(config('payflow.database.connection'));
    }
}
