<?php

namespace Payflow\Actions\Carts;

use Payflow\Actions\AbstractAction;
use Payflow\DataTypes\ShippingOption;
use Payflow\Models\Cart;
use Payflow\Models\CartLine;

class SetShippingOption extends AbstractAction
{
    /**
     * Execute the action.
     *
     * @param  CartLine  $cartLine
     * @param  ShippingOption  $customerGroups
     */
    public function execute(
        Cart $cart,
        ShippingOption $shippingOption
    ): self {
        $cart->shippingAddress->shippingOption = $shippingOption;
        $cart->shippingAddress->update([
            'shipping_option' => $shippingOption->getIdentifier(),
        ]);

        return $this;
    }
}
