<?php

namespace Payflow\Base\ValueObjects\Cart;

use Payflow\DataTypes\Price;

class TaxBreakdownAmount
{
    public function __construct(
        public Price $price,
        public string $identifier,
        public string $description,
        public float $percentage,
    ) {
        //
    }
}
