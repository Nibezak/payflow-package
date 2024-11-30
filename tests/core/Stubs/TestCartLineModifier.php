<?php

namespace Payflow\Tests\Core\Stubs;

use Closure;
use Payflow\Base\CartLineModifier;
use Payflow\DataTypes\Price;
use Payflow\Models\CartLine;

class TestCartLineModifier extends CartLineModifier
{
    public function calculating(CartLine $cartLine, Closure $next): CartLine
    {
        $cartLine->unitPrice = new Price(1000, $cartLine->cart->currency, 1);

        return $next($cartLine);
    }
}
