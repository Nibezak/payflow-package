<?php

namespace Payflow\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Payflow\Base\BaseModel;
use Payflow\Base\Casts\AsAttributeData;
use Payflow\Base\Traits\HasChannels;
use Payflow\Base\Traits\HasCustomerGroups;
use Payflow\Base\Traits\HasMacros;
use Payflow\Base\Traits\HasMedia;
use Payflow\Base\Traits\HasTags;
use Payflow\Base\Traits\HasTranslations;
use Payflow\Base\Traits\HasUrls;
use Payflow\Base\Traits\LogsActivity;
use Payflow\Base\Traits\Searchable;
use Payflow\Database\Factories\ProductFactory;
use Payflow\Jobs\Products\Associations\Associate;
use Payflow\Jobs\Products\Associations\Dissociate;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property int $id
 * @property ?int $brand_id
 * @property int $product_type_id
 * @property string $status
 * @property array $attribute_data
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 * @property ?\Illuminate\Support\Carbon $deleted_at
 */
class Product extends BaseModel implements Contracts\Product, SpatieHasMedia
{
    use HasChannels;
    use HasCustomerGroups;
    use HasFactory;
    use HasMacros;
    use HasMedia;
    use HasTags;
    use HasTranslations;
    use HasUrls;
    use LogsActivity;
    use Searchable;
    use SoftDeletes;

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return ProductFactory::new();
    }

    /**
     * Define which attributes should be
     * fillable during mass assignment.
     *
     * @var array
     */
    protected $fillable = [
        'attribute_data',
        'product_type_id',
        'status',
        'brand_id',
    ];

    /**
     * Define which attributes should be cast.
     *
     * @var array
     */
    protected $casts = [
        'attribute_data' => AsAttributeData::class,
    ];

    /**
     * Record's title
     */
    protected function recordTitle(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $this->translateAttribute('name'),
        );
    }

    public function mappedAttributes(): Collection
    {
        return $this->productType->mappedAttributes;
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::modelClass());
    }

    public function images(): MorphMany
    {
        return $this->media()->where('collection_name', config('payflow.media.collection'));
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::modelClass());
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(
            \Payflow\Models\Collection::modelClass(),
            config('payflow.database.table_prefix').'collection_product'
        )->withPivot(['position'])->withTimestamps();
    }

    public function associations(): HasMany
    {
        return $this->hasMany(ProductAssociation::modelClass(), 'product_parent_id');
    }

    public function inverseAssociations(): HasMany
    {
        return $this->hasMany(ProductAssociation::modelClass(), 'product_target_id');
    }

    public function associate(mixed $product, string $type): void
    {
        Associate::dispatch($this, $product, $type);
    }

    /**
     * Dissociate a product to another with a type.
     */
    public function dissociate(mixed $product, ?string $type = null): void
    {
        Dissociate::dispatch($this, $product, $type);
    }

    public function customerGroups(): BelongsToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->belongsToMany(
            CustomerGroup::modelClass(),
            "{$prefix}customer_group_product"
        )->withPivot([
            'purchasable',
            'visible',
            'enabled',
            'starts_at',
            'ends_at',
        ])->withTimestamps();
    }

    public static function getExtraCustomerGroupPivotValues(CustomerGroup $customerGroup): array
    {
        return [
            'purchasable' => $customerGroup->default,
        ];
    }

    /**
     * Return the brand relationship.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::modelClass());
    }

    public function scopeStatus(Builder $query, string $status): Builder
    {
        return $query->whereStatus($status);
    }

    public function prices(): HasManyThrough
    {
        return $this->hasManyThrough(
            Price::modelClass(),
            ProductVariant::modelClass(),
            'product_id',
            'priceable_id'
        )->wherePriceableType('product_variant');
    }

    public function productOptions(): BelongsToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->belongsToMany(
            ProductOption::modelClass(),
            "{$prefix}product_product_option"
        )->withPivot(['position'])->orderByPivot('position');
    }
}
