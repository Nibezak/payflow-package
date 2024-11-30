<?php

namespace Payflow\Shipping;

use Payflow\Facades\ShippingManifest;
use Payflow\Models\Cart;
use Payflow\Shipping\DataTransferObjects\ShippingOptionLookup;
use Payflow\Shipping\Facades\Shipping;

class ShippingModifier
{
    public function handle(Cart $cart, \Closure $next)
    {
        $shippingRates = Shipping::shippingRates($cart)->get();

        $options = Shipping::shippingOptions($cart)->get(
            new ShippingOptionLookup(
                shippingRates: $shippingRates
            )
        );

        foreach ($options as $option) {
            ShippingManifest::addOption($option->option);
        }

        return $next($cart);
    }
}
