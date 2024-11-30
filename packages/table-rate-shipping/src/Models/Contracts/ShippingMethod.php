<?php

namespace Payflow\Shipping\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Payflow\Shipping\Interfaces\ShippingRateInterface;

interface ShippingMethod
{
    public function shippingRates(): HasMany;

    public function driver(): ShippingRateInterface;
}
