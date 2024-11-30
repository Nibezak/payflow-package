<?php

namespace Payflow\Base;

use Closure;
use Payflow\Models\Cart;

abstract class ShippingModifier
{
    /**
     * Called just before cart totals are calculated.
     *
     * @return void
     */
    public function handle(Cart $cart, Closure $next)
    {
        //
    }
}
