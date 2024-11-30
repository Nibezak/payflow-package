<?php

namespace Payflow\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Payflow\Base\BaseModel;
use Payflow\Base\Casts\AsAttributeData;
use Payflow\Base\Traits\HasAttributes;
use Payflow\Base\Traits\HasDefaultRecord;
use Payflow\Base\Traits\HasMacros;
use Payflow\Database\Factories\CustomerGroupFactory;

/**
 * @property int $id
 * @property string $name
 * @property string $handle
 * @property bool $default
 * @property ?array $attribute_data
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class CustomerGroup extends BaseModel implements Contracts\CustomerGroup
{
    use HasAttributes;
    use HasDefaultRecord;
    use HasFactory;
    use HasMacros;

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'attribute_data' => AsAttributeData::class,
    ];

    /**
     * {@inheritDoc}
     */
    protected $guarded = [];

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return CustomerGroupFactory::new();
    }

    public function customers(): BelongsToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->belongsToMany(
            Customer::modelClass(),
            "{$prefix}customer_customer_group"
        )->withTimestamps();
    }

    /**
     * Return the discounts relationship.
     */
    public function discounts(): BelongsToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->belongsToMany(
            Discount::modelClass(),
            "{$prefix}customer_group_discount"
        )->withTimestamps();
    }

    /**
     * Return the product relationship.
     */
    public function products(): BelongsToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->belongsToMany(
            Product::modelClass(),
            "{$prefix}customer_group_product"
        )->withTimestamps();
    }

    public function collections(): BelongsToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->belongsToMany(
            Collection::modelClass(),
            "{$prefix}collection_customer_group"
        )->withTimestamps();
    }

    /**
     * Get the mapped attributes relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function mappedAttributes()
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->morphToMany(
            Attribute::class,
            'attributable',
            "{$prefix}attributables"
        )->withTimestamps();
    }
}
