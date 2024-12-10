<?php

namespace Payflow\Base;

use App\Models\Scopes\TenantScope;
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

    /**
     * Boot the model to apply the TenantScope globally.
     */
    protected static function boot()
    {
        parent::boot();

        // Add the TenantScope globally
        static::addGlobalScope(new TenantScope());

        // Handle the "creating" event to set tenant_id dynamically
        static::creating(function ($model) {
            (new TenantScope())->creating($model);
        });
    }
}
