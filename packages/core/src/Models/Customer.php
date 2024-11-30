<?php

namespace Payflow\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Payflow\Base\BaseModel;
use Payflow\Base\Casts\AsAttributeData;
use Payflow\Base\Traits\HasAttributes;
use Payflow\Base\Traits\HasMacros;
use Payflow\Base\Traits\HasPersonalDetails;
use Payflow\Base\Traits\HasTranslations;
use Payflow\Base\Traits\Searchable;
use Payflow\Database\Factories\CustomerFactory;

/**
 * @property int $id
 * @property ?string $title
 * @property string $first_name
 * @property string $last_name
 * @property ?string $company_name
 * @property ?string $vat_no
 * @property ?string $account_ref
 * @property ?array $attribute_data
 * @property ?array $meta
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Customer extends BaseModel implements Contracts\Customer
{
    use HasAttributes;
    use HasFactory;
    use HasMacros;
    use HasPersonalDetails;
    use HasTranslations;
    use Searchable;

    /**
     * Define the guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'attribute_data' => AsAttributeData::class,
        'meta' => AsArrayObject::class,
    ];

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return CustomerFactory::new();
    }

    public function customerGroups(): BelongsToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->belongsToMany(
            CustomerGroup::modelClass(),
            "{$prefix}customer_customer_group"
        )->withTimestamps();
    }

    public function users(): BelongsToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->belongsToMany(
            config('auth.providers.users.model'),
            "{$prefix}customer_user"
        )->withTimestamps();
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::modelClass());
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::modelClass());
    }

    public function mappedAttributes(): MorphToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->morphToMany(
            Attribute::modelClass(),
            'attributable',
            "{$prefix}attributables"
        )->withTimestamps();
    }
}
