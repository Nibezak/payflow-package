<?php

namespace Payflow\Shipping\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Payflow\DataTypes\ShippingOption;
use Payflow\Models\Cart;
use Payflow\Shipping\Models\ShippingRate;

class ShippingOptionResolvedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The resolved shipping option.
     */
    public ShippingOption $shippingOption;

    /**
     * The instance of the shipping method.
     */
    public ShippingRate $shippingRate;

    /**
     * The instance of the cart.
     */
    public Cart $cart;

    public function __construct(Cart $cart, ShippingRate $shippingRate, ShippingOption $shippingOption)
    {
        $this->cart = $cart;
        $this->shippingRate = $shippingRate;
        $this->shippingOption = $shippingOption;
    }
}
