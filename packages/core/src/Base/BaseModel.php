<?php

namespace Payflow\Base;

use Illuminate\Database\Eloquent\Model;
use Payflow\Base\Traits\HasModelExtending;

abstract class BaseModel extends Model
{
    use HasModelExtending;

    /**
     * Create a new instance of the Model.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable(config('payflow.database.table_prefix').$this->getTable());

        if ($connection = config('payflow.database.connection')) {
            $this->setConnection($connection);
        }
    }
}
