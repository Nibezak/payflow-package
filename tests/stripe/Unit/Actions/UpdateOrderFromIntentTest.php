<?php

uses(\Payflow\Tests\Stripe\Unit\TestCase::class);

it('creates pending transaction when status is requires_action', function () {

    $cart = \Payflow\Tests\Stripe\Utils\CartBuilder::build();

    $order = $cart->createOrder();

    $paymentIntent = \Payflow\Stripe\Facades\Stripe::getClient()
        ->paymentIntents
        ->retrieve('PI_REQUIRES_ACTION');

    $updatedOrder = \Payflow\Stripe\Actions\UpdateOrderFromIntent::execute($order, $paymentIntent);
    expect($updatedOrder->status)->toBe($order->status);
    expect($updatedOrder->placed_at)->toBeNull();
})->group('payflow.stripe.actions');
