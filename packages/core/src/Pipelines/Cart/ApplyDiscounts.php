<?php

namespace Payflow\Pipelines\Cart;

use Closure;
use Payflow\Facades\Discounts;
use Payflow\Models\Cart;

final class ApplyDiscounts
{
    /**
     * Called just before cart totals are calculated.
     *
     * @return mixed
     */
    public function handle(Cart $cart, Closure $next)
    {
        $cart->discounts = collect([]);
        $cart->discountBreakdown = collect([]);

        Discounts::apply($cart);

        return $next($cart);
    }
}
