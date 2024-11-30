<?php

namespace Payflow\Actions\Carts;

use Illuminate\Pipeline\Pipeline;
use Payflow\Actions\AbstractAction;
use Payflow\Exceptions\DisallowMultipleCartOrdersException;
use Payflow\Facades\DB;
use Payflow\Jobs\Orders\MarkAsNewCustomer;
use Payflow\Models\Cart;
use Payflow\Models\Order;

final class CreateOrder extends AbstractAction
{
    /**
     * Execute the action.
     */
    public function execute(
        Cart $cart,
        bool $allowMultipleOrders = false,
        ?int $orderIdToUpdate = null
    ): self {
        $this->passThrough = DB::transaction(function () use ($cart, $allowMultipleOrders, $orderIdToUpdate) {
            $order = $cart->currentDraftOrder($orderIdToUpdate) ?: new Order;

            if ($cart->hasCompletedOrders() && ! $allowMultipleOrders) {
                throw new DisallowMultipleCartOrdersException;
            }

            $order->fill([
                'cart_id' => $cart->id,
                'fingerprint' => $cart->fingerprint(),
            ]);

            $order = app(Pipeline::class)
                ->send($order)
                ->through(
                    config('payflow.orders.pipelines.creation', [])
                )->thenReturn(function ($order) {
                    return $order;
                });

            $cart->discounts?->each(function ($discount) use ($cart) {
                $discount->markAsUsed($cart)->discount->save();
            });

            $cart->save();

            MarkAsNewCustomer::dispatch($order->id);

            $order->refresh();

            return $order;
        });

        return $this;
    }
}
