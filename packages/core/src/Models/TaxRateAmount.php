<?php

namespace Payflow\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Payflow\Base\BaseModel;
use Payflow\Base\Traits\HasMacros;
use Payflow\Database\Factories\TaxRateAmountFactory;

/**
 * @property int $id
 * @property ?int $tax_class_id
 * @property ?int $tax_rate_id
 * @property float $percentage
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class TaxRateAmount extends BaseModel implements Contracts\TaxRateAmount
{
    use HasFactory;
    use HasMacros;

    /**
     * The tax rate amount.
     *
     * @var Price|null
     */
    public $total;

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return TaxRateAmountFactory::new();
    }

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Return the tax rate relation.
     */
    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(TaxRate::modelClass());
    }

    /**
     * Return the tax class relation.
     */
    public function taxClass(): BelongsTo
    {
        return $this->belongsTo(TaxClass::modelClass());
    }
}
