<?php

uses(\Payflow\Tests\Stripe\Unit\TestCase::class);

it('can store successful charge', function () {
    $cart = \Payflow\Tests\Stripe\Utils\CartBuilder::build();

    $order = $cart->createOrder();

    $paymentIntent = \Payflow\Stripe\Facades\Stripe::getClient()
        ->paymentIntents
        ->retrieve('PI_CAPTURE');

    $charges = collect($paymentIntent->charges->data);

    $order = app(\Payflow\Stripe\Actions\StoreCharges::class)->store($order, $charges);

    expect($order->transactions)->toHaveCount(1);

    $charge = $charges->first();
    $transaction = $order->transactions->first();

    expect($transaction->type)->toBe('capture');
    expect($transaction->amount->value)->toBe($charge->amount);
    expect($transaction->reference)->toBe($charge->id);
})->group('payflow.stripe.actions');

it('updates existing transactions', function () {
    $cart = \Payflow\Tests\Stripe\Utils\CartBuilder::build();

    $order = $cart->createOrder();

    $paymentIntent = \Payflow\Stripe\Facades\Stripe::getClient()
        ->paymentIntents
        ->retrieve('PI_CAPTURE');

    $charges = collect($paymentIntent->charges->data);

    $order = app(\Payflow\Stripe\Actions\StoreCharges::class)->store($order, $charges);

    expect($order->transactions)->toHaveCount(1);

    $order = app(\Payflow\Stripe\Actions\StoreCharges::class)->store($order, $charges);

    expect($order->transactions)->toHaveCount(1);

})->group('payflow.stripe.actions');
