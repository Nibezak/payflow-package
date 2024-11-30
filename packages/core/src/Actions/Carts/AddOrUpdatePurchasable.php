<?php

namespace Payflow\Actions\Carts;

use Payflow\Actions\AbstractAction;
use Payflow\Base\Purchasable;
use Payflow\Exceptions\InvalidCartLineQuantityException;
use Payflow\Models\Cart;

class AddOrUpdatePurchasable extends AbstractAction
{
    /**
     * Execute the action.
     *
     * @param  \Payflow\Models\CartLine  $cartLine
     * @param  \Illuminate\Database\Eloquent\Collection  $customerGroups
     * @return \Payflow\Models\CartLine
     */
    public function execute(
        Cart $cart,
        Purchasable $purchasable,
        int $quantity = 1,
        array $meta = []
    ): self {
        throw_if(! $quantity, InvalidCartLineQuantityException::class);

        $existing = app(
            config('payflow.cart.actions.get_existing_cart_line', GetExistingCartLine::class)
        )->execute(
            cart: $cart,
            purchasable: $purchasable,
            meta: $meta
        );

        if ($existing) {
            $existing->update([
                'quantity' => $existing->quantity + $quantity,
            ]);

            return $this;
        }

        $cart->lines()->create([
            'purchasable_id' => $purchasable->id,
            'purchasable_type' => $purchasable->getMorphClass(),
            'quantity' => $quantity,
            'meta' => $meta,
        ]);

        return $this;
    }
}
