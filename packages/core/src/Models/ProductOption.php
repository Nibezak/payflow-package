<?php

namespace Payflow\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Payflow\Base\BaseModel;
use Payflow\Base\Traits\HasMacros;
use Payflow\Base\Traits\HasMedia;
use Payflow\Base\Traits\HasTranslations;
use Payflow\Base\Traits\Searchable;
use Payflow\Database\Factories\ProductOptionFactory;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property int $id
 * @property \Illuminate\Support\Collection $name
 * @property \Illuminate\Support\Collection $label
 * @property int $position
 * @property ?string $handle
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class ProductOption extends BaseModel implements Contracts\ProductOption, SpatieHasMedia
{
    use HasFactory;
    use HasMacros;
    use HasMedia;
    use HasTranslations;
    use Searchable;

    /**
     * Define which attributes should be cast.
     *
     * @var array
     */
    protected $casts = [
        'name' => AsArrayObject::class,
        'label' => AsArrayObject::class,
        'shared' => 'boolean',
    ];

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return ProductOptionFactory::new();
    }

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    public function scopeShared(Builder $builder): Builder
    {
        return $builder->where('shared', '=', true);
    }

    public function scopeExclusive(Builder $builder): Builder
    {
        return $builder->where('shared', '=', false);
    }

    public function values(): HasMany
    {
        return $this->hasMany(ProductOptionValue::modelClass())->orderBy('position');
    }

    public function products(): BelongsToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->belongsToMany(
            Product::modelClass(),
            "{$prefix}product_product_option"
        )->withPivot(['position'])->orderByPivot('position');
    }
}
