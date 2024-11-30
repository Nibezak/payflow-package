<?php

namespace Payflow\Shipping\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Payflow\DataTypes\ShippingOption;
use Payflow\Models\Cart;

interface ShippingRate
{
    public function shippingZone(): BelongsTo;

    public function shippingMethod(): BelongsTo;

    /**
     * Return the shipping method driver.
     */
    public function getShippingOption(Cart $cart): ?ShippingOption;
}
