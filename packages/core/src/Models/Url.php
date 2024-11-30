<?php

namespace Payflow\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Payflow\Base\BaseModel;
use Payflow\Base\Traits\HasMacros;
use Payflow\Database\Factories\UrlFactory;

/**
 * @property int $id
 * @property int $language_id
 * @property string $element_type
 * @property int $element_id
 * @property string $slug
 * @property bool $default
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Url extends BaseModel implements Contracts\Url
{
    use HasFactory;
    use HasMacros;

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return UrlFactory::new();
    }

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define attribute casting.
     *
     * @var array
     */
    protected $casts = [
        'default' => 'boolean',
    ];

    /**
     * Return the element relationship.
     */
    public function element(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Return the language relationship.
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::modelClass());
    }

    /**
     * Return the query scope for default.
     */
    public function scopeDefault(Builder $query): Builder
    {
        return $query->whereDefault(true);
    }
}
