<?php

namespace Payflow\Observers;

use Payflow\Base\Purchasable;
use Payflow\Exceptions\NonPurchasableItemException;
use Payflow\Models\CartLine;

class CartLineObserver
{
    /**
     * Handle the CartLine "creating" event.
     *
     * @return void
     */
    public function creating(CartLine $cartLine)
    {
        if (! $cartLine->purchasable instanceof Purchasable) {
            throw new NonPurchasableItemException($cartLine->purchasable_type);
        }
    }

    /**
     * Handle the CartLine "updated" event.
     *
     * @return void
     */
    public function updating(CartLine $cartLine)
    {
        if (! $cartLine->purchasable instanceof Purchasable) {
            throw new NonPurchasableItemException($cartLine->purchasable_type);
        }
    }
}
