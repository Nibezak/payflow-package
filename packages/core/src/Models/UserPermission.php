<?php

namespace Payflow\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Payflow\Base\BaseModel;
use Payflow\Base\Traits\HasMacros;

class UserPermission extends BaseModel implements Contracts\UserPermission
{
    use HasMacros;

    protected $fillable = ['handle'];

    /**
     * Return the user relationship.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
