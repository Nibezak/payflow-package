<?php

namespace Payflow\Actions\Carts;

use Payflow\Actions\AbstractAction;
use Payflow\Base\Purchasable;
use Payflow\Models\Cart;
use Payflow\Models\CartLine;
use Payflow\Utils\Arr;

class GetExistingCartLine extends AbstractAction
{
    /**
     * Execute the action
     */
    public function execute(
        Cart $cart,
        Purchasable $purchasable,
        array $meta = []
    ): ?CartLine {
        // Get all possible cart lines
        $lines = $cart->lines()
            ->wherePurchasableType(
                $purchasable->getMorphClass()
            )->wherePurchasableId($purchasable->id)
            ->get();

        return $lines->first(function ($line) use ($meta) {
            $diff = Arr::diff($line->meta, $meta);

            return empty($diff->new) && empty($diff->edited) & empty($diff->removed);
        });
    }
}
