<?php

namespace Payflow\Base\DataTransferObjects;

use Payflow\Models\Cart;
use Payflow\Models\CartLine;
use Payflow\Models\Discount;

class CartDiscount
{
    public function __construct(
        public CartLine|Cart $model,
        public Discount $discount
    ) {
        //
    }
}
