<?php

namespace Payflow\Base;

use Payflow\Models\Cart;
use Payflow\Models\CartLine;

interface DiscountTypeInterface
{
    /**
     * Return the name of the discount type.
     */
    public function getName(): string;

    /**
     * Execute and apply the discount if conditions are met.
     *
     * @param  CartLine  $cartLine
     * @return CartLine
     */
    public function apply(Cart $cart): Cart;
}
