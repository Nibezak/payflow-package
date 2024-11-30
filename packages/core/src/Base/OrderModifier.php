<?php

namespace Payflow\Base;

use Closure;
use Payflow\Models\Cart;
use Payflow\Models\Order;

abstract class OrderModifier
{
    public function creating(Cart $cart, Closure $next): Cart
    {
        return $next($cart);
    }

    public function created(Order $order, Closure $next): Order
    {
        return $next($order);
    }
}
