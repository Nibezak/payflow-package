<?php

namespace Payflow\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Payflow\Base\BaseModel;
use Payflow\Base\Traits\HasMacros;
use Payflow\Base\Traits\HasTranslations;
use Payflow\Database\Factories\AttributeFactory;
use Payflow\Facades\DB;

/**
 * @property int $id
 * @property string $attribute_type
 * @property int $attribute_group_id
 * @property int $position
 * @property string $name
 * @property string $handle
 * @property string $section
 * @property string $type
 * @property bool $required
 * @property ?string $default_value
 * @property string $configuration
 * @property bool $system
 * @property string $validation_rules
 * @property bool $filterable
 * @property bool $searchable
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Attribute extends BaseModel implements Contracts\Attribute
{
    use HasFactory;
    use HasMacros;
    use HasTranslations;

    protected static function booted(): void
    {
        static::deleting(function (self $attribute) {
            DB::beginTransaction();
            DB::table(
                config('payflow.database.table_prefix').'attributables'
            )->where('attribute_id', '=', $attribute->id)->delete();
            DB::commit();
        });
    }

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return AttributeFactory::new();
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
        'description' => AsCollection::class,
        'configuration' => AsCollection::class,
    ];

    public function attributable(): MorphTo
    {
        return $this->morphTo();
    }

    public function attributeGroup(): BelongsTo
    {
        return $this->belongsTo(AttributeGroup::modelClass());
    }

    public function scopeSystem(Builder $query, $type): Builder
    {
        return $query->whereAttributeType($type)->whereSystem(true);
    }
}
