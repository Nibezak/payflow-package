<?php

namespace Payflow\Tests\Core\Stubs;

use Closure;
use Payflow\Base\CartModifier;
use Payflow\Models\Cart;

class TestCartModifier extends CartModifier
{
    /**
     * Called just after cart totals are calculated.
     *
     * @return void
     */
    public function calculated(Cart $cart, Closure $next): Cart
    {
        $cart->total->value = 5000;

        return $next($cart);
    }
}
