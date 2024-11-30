<?php

namespace Payflow\Base\ValueObjects\Cart;

use Illuminate\Support\Collection;
use Payflow\DataTypes\Price;
use Payflow\Models\Discount;

class DiscountBreakdown
{
    public function __construct(
        public Price $price,
        public Collection $lines,
        public Discount $discount,
    ) {
        //
    }
}
