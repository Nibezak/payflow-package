<?php

namespace Payflow\Actions\Carts;

use Payflow\Actions\AbstractAction;
use Payflow\Exceptions\CartLineIdMismatchException;
use Payflow\Facades\DB;
use Payflow\Models\Cart;

class RemovePurchasable extends AbstractAction
{
    /**
     * Execute the action
     *
     * @return bool
     *
     * @throws CartLineIdMismatchException
     */
    public function execute(
        Cart $cart,
        int $cartLineId
    ): self {
        DB::transaction(function () use ($cart, $cartLineId) {
            $line = $cart->lines()->whereId($cartLineId)->first();

            if (! $line) {
                // If we're trying to remove a line that does not
                // belong to this cart, throw an exception.
                throw new CartLineIdMismatchException(
                    __('payflow::exceptions.cart_line_id_mismatch')
                );
            }

            $line->delete();
        });

        return $this;
    }
}
