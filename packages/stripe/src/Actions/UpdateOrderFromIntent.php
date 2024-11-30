<?php

namespace Payflow\Stripe\Actions;

use Illuminate\Support\Facades\DB;
use Payflow\Models\Order;
use Payflow\Stripe\Facades\Stripe;
use Stripe\PaymentIntent;

class UpdateOrderFromIntent
{
    final public static function execute(
        Order $order,
        PaymentIntent $paymentIntent,
        string $successStatus = 'paid',
        string $failStatus = 'failed'
    ): Order {
        return DB::transaction(function () use ($order, $paymentIntent) {

            $charges = Stripe::getCharges($paymentIntent->id);

            $order = app(StoreCharges::class)->store($order, $charges);
            $requiresCapture = $paymentIntent->status === PaymentIntent::STATUS_REQUIRES_CAPTURE;

            $statuses = config('payflow.stripe.status_mapping', []);

            $placedAt = null;

            if ($paymentIntent->status === PaymentIntent::STATUS_SUCCEEDED) {
                $placedAt = now();
            }

            if ($charges->isEmpty() && ! $requiresCapture) {
                return $order;
            }

            if (config('payflow.stripe.sync_addresses', true) && $paymentIntent->payment_method) {
                (new StoreAddressInformation)->store($order, $paymentIntent);
            }

            $order->update([
                'status' => $statuses[$paymentIntent->status] ?? $paymentIntent->status,
                'placed_at' => $order->placed_at ?: $placedAt,
            ]);

            return $order;
        });
    }
}
