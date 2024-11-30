<?php

namespace Payflow\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Payflow\Base\BaseModel;
use Payflow\Base\Traits\HasMacros;
use Payflow\Database\Factories\CollectionGroupFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $handle
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class CollectionGroup extends BaseModel implements Contracts\CollectionGroup
{
    use HasFactory;
    use HasMacros;

    protected $guarded = [];

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return CollectionGroupFactory::new();
    }

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::modelClass());
    }
}
