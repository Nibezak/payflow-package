<?php

namespace Payflow\Observers;

use Payflow\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "updating" event.
     *
     * @return void
     */
    public function updating(Order $order)
    {
        if ($order->getOriginal('status') != $order->status) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($order)
                ->event('status-update')
                ->withProperties([
                    'new' => $order->status,
                    'previous' => $order->getOriginal('status'),
                ])->log('status-update');
        }
    }
}
