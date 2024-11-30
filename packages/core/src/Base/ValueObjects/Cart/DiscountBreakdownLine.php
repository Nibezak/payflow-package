<?php

namespace Payflow\Base\ValueObjects\Cart;

use Payflow\Models\CartLine;

class DiscountBreakdownLine
{
    public function __construct(
        public CartLine $line,
        public int $quantity,
    ) {
        //
    }
}
