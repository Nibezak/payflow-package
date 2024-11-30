<?php

namespace Payflow\Base;

use Closure;
use Payflow\Models\Cart;

abstract class CartModifier
{
    /**
     * Called just before cart totals are calculated.
     *
     * @return void
     */
    public function calculating(Cart $cart, Closure $next): Cart
    {
        return $next($cart);
    }

    /**
     * Called just after cart totals are calculated.
     *
     * @return void
     */
    public function calculated(Cart $cart, Closure $next): Cart
    {
        return $next($cart);
    }
}
