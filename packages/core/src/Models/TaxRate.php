<?php

namespace Payflow\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Payflow\Base\BaseModel;
use Payflow\Base\Traits\HasMacros;
use Payflow\Database\Factories\TaxRateFactory;
use Payflow\Facades\DB;

/**
 * @property int $id
 * @property ?int $tax_zone_id
 * @property bool $priority
 * @property string $name
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class TaxRate extends BaseModel implements Contracts\TaxRate
{
    use HasFactory;
    use HasMacros;

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return TaxRateFactory::new();
    }

    protected static function booted(): void
    {
        static::deleting(function (self $taxRate) {
            DB::beginTransaction();
            $taxRate->taxRateAmounts()->delete();
            DB::commit();
        });
    }

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Return the tax zone relation.
     */
    public function taxZone(): BelongsTo
    {
        return $this->belongsTo(TaxZone::modelClass());
    }

    /**
     * Return the tax rate amounts relation.
     */
    public function taxRateAmounts(): HasMany
    {
        return $this->hasMany(TaxRateAmount::modelClass());
    }
}
