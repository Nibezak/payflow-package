<?php

namespace Payflow\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Kalnoy\Nestedset\NodeTrait;
use Kalnoy\Nestedset\QueryBuilder;
use Payflow\Base\BaseModel;
use Payflow\Base\Casts\AsAttributeData;
use Payflow\Base\Traits\HasChannels;
use Payflow\Base\Traits\HasCustomerGroups;
use Payflow\Base\Traits\HasMacros;
use Payflow\Base\Traits\HasMedia;
use Payflow\Base\Traits\HasTranslations;
use Payflow\Base\Traits\HasUrls;
use Payflow\Base\Traits\Searchable;
use Payflow\Database\Factories\CollectionFactory;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property int $id
 * @property int $collection_group_id
 * @property-read  int $_lft
 * @property-read  int $_rgt
 * @property ?int $parent_id
 * @property string $type
 * @property ?array $attribute_data
 * @property string $sort
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 * @property ?\Illuminate\Support\Carbon $deleted_at
 */
class Collection extends BaseModel implements Contracts\Collection, SpatieHasMedia
{
    use HasChannels,
        HasCustomerGroups,
        HasFactory,
        HasMacros,
        HasMedia,
        HasTranslations,
        HasUrls,
        NodeTrait,
        Searchable {
            NodeTrait::usesSoftDelete insteadof Searchable;
        }

    /**
     * Define which attributes should be cast.
     *
     * @var array
     */
    protected $casts = [
        'attribute_data' => AsAttributeData::class,
    ];

    protected $guarded = [];

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return CollectionFactory::new();
    }

    public function getScopeAttributes()
    {
        return ['collection_group_id'];
    }

    /**
     * Return the group relationship.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(CollectionGroup::modelClass(), 'collection_group_id');
    }

    public function scopeInGroup(Builder $builder, int $id): Builder
    {
        return $builder->where('collection_group_id', $id);
    }

    /**
     * Return the products relationship.
     */
    public function products(): BelongsToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->belongsToMany(
            Product::modelClass(),
            "{$prefix}collection_product"
        )->withPivot([
            'position',
        ])->withTimestamps()->orderByPivot('position');
    }

    /**
     * Get the translated name of ancestor collections.
     */
    public function getBreadcrumbAttribute(): \Illuminate\Support\Collection
    {
        return $this->ancestors->map(function ($ancestor) {
            return $ancestor->translateAttribute('name');
        });
    }

    public function customerGroups(): BelongsToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->belongsToMany(
            CustomerGroup::modelClass(),
            "{$prefix}collection_customer_group"
        )->withPivot([
            'visible',
            'enabled',
            'starts_at',
            'ends_at',
        ])->withTimestamps();
    }

    public function discounts(): BelongsToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->belongsToMany(
            Discount::modelClass(),
            "{$prefix}collection_discount"
        )->withPivot(['type'])->withTimestamps();
    }

    public function brands(): BelongsToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->belongsToMany(
            Brand::modelClass(),
            "{$prefix}brand_collection"
        )->withTimestamps();
    }

    public function newEloquentBuilder($query): QueryBuilder
    {
        return new QueryBuilder($query);
    }
}
