<?php

namespace Payflow\Actions\Orders;

use Payflow\Models\Order;

class GenerateOrderReference
{
    /**
     * Execute the action.
     *
     * @param  \Payflow\Models\CartLine  $cartLine
     * @param  \Illuminate\Database\Eloquent\Collection  $customerGroups
     * @return \Payflow\Models\CartLine
     */
    public function execute(
        Order $order
    ) {
        $generator = config('payflow.orders.reference_generator');

        if (! $generator) {
            return null;
        }

        return app($generator)->generate($order);
    }
}
