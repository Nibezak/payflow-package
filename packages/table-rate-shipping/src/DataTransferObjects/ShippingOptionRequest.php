<?php

namespace Payflow\Shipping\DataTransferObjects;

use Payflow\Models\Cart;
use Payflow\Shipping\Models\ShippingRate;

class ShippingOptionRequest
{
    /**
     * Initialise the shipping option request class.
     */
    public function __construct(
        public ShippingRate $shippingRate,
        public Cart $cart
    ) {
        //
    }
}
