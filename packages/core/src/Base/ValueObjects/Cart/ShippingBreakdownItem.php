<?php

namespace Payflow\Base\ValueObjects\Cart;

use Payflow\DataTypes\Price;

class ShippingBreakdownItem
{
    public function __construct(
        public string $name,
        public string $identifier,
        public Price $price
    ) {
        //
    }
}
