<?php

namespace Payflow\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Payflow\Base\BaseModel;
use Payflow\Base\Traits\HasMacros;
use Payflow\Base\Traits\HasTranslations;
use Payflow\Database\Factories\AttributeGroupFactory;

/**
 * @property int $id
 * @property string $attributable_type
 * @property string $name
 * @property string $handle
 * @property int $position
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class AttributeGroup extends BaseModel implements Contracts\AttributeGroup
{
    use HasFactory;
    use HasMacros;
    use HasTranslations;

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return AttributeGroupFactory::new();
    }

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define which attributes should be cast.
     *
     * @var array
     */
    protected $casts = [
        'name' => AsCollection::class,
    ];

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::modelClass())->orderBy('position');
    }
}
