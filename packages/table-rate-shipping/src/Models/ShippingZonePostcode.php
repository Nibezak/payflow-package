<?php

namespace Payflow\Shipping\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Payflow\Base\BaseModel;
use Payflow\Shipping\Factories\ShippingZonePostcodeFactory;

class ShippingZonePostcode extends BaseModel implements Contracts\ShippingZonePostcode
{
    use HasFactory;

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [];

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return ShippingZonePostcodeFactory::new();
    }

    /**
     * Return the shipping zone relationship.
     */
    public function shippingZone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::modelClass());
    }

    /**
     * Setter for postcode attribute.
     */
    public function setPostcodeAttribute(?string $value): void
    {
        $this->attributes['postcode'] = str_replace(' ', '', $value);
    }
}
