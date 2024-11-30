<?php

use Payflow\Stripe\Facades\Stripe;
use Payflow\Tests\Stripe\Utils\CartBuilder;

use function Pest\Laravel\assertDatabaseHas;

uses(\Payflow\Tests\Stripe\Unit\TestCase::class);

it('can create a payment intent', function () {
    $cart = CartBuilder::build();

    $intent = Stripe::createIntent($cart->calculate(), []);

    assertDatabaseHas(\Payflow\Stripe\Models\StripePaymentIntent::class, [
        'intent_id' => 'pi_1DqH152eZvKYlo2CFHYZuxkP',
        'cart_id' => $cart->id,
        'status' => $intent->status,
    ]);
});
