<?php

namespace Payflow\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Payflow\Base\BaseModel;
use Payflow\Base\Casts\AsAttributeData;
use Payflow\Base\Traits\HasAttributes;
use Payflow\Base\Traits\HasMacros;
use Payflow\Base\Traits\HasMedia;
use Payflow\Base\Traits\HasTranslations;
use Payflow\Base\Traits\HasUrls;
use Payflow\Base\Traits\LogsActivity;
use Payflow\Base\Traits\Searchable;
use Payflow\Database\Factories\BrandFactory;
use Payflow\Facades\DB;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property int $id
 * @property string $name
 * @property ?array $attribute_data
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Brand extends BaseModel implements Contracts\Brand, SpatieHasMedia
{
    use HasAttributes;
    use HasFactory;
    use HasMacros;
    use HasMedia;
    use HasTranslations;
    use HasUrls;
    use LogsActivity;
    use Searchable;

    /**
     * {@inheritDoc}
     */
    protected $guarded = [];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'attribute_data' => AsAttributeData::class,
    ];

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return BrandFactory::new();
    }

    protected static function booted(): void
    {
        static::deleting(function (self $brand) {
            DB::beginTransaction();
            $brand->discounts()->detach();
            $brand->collections()->detach();
            DB::commit();
        });
    }

    /**
     * Return the product relationship.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::modelClass());
    }

    public function discounts()
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->belongsToMany(Discount::modelClass(), "{$prefix}brand_discount");
    }

    public function collections(): BelongsToMany
    {
        $prefix = config('payflow.database.table_prefix');

        return $this->belongsToMany(Collection::modelClass(), "{$prefix}brand_collection");
    }
}
