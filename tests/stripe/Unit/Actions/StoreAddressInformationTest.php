<?php

use function Pest\Laravel\assertDatabaseHas;

uses(\Payflow\Tests\Stripe\Unit\TestCase::class);

it('can store payment intent address information', function () {
    $cart = \Payflow\Tests\Stripe\Utils\CartBuilder::build();

    $country = \Payflow\Models\Country::factory()->create([
        'iso2' => 'GB',
    ]);

    $order = $cart->createOrder();

    $paymentIntent = \Payflow\Stripe\Facades\Stripe::getClient()
        ->paymentIntents
        ->retrieve('PI_CAPTURE');

    app(\Payflow\Stripe\Actions\StoreAddressInformation::class)->store($order, $paymentIntent);

    assertDatabaseHas(\Payflow\Models\OrderAddress::class, [
        'first_name' => 'Buggs',
        'last_name' => 'Bunny',
        'city' => 'ACME Shipping Land',
        'type' => 'shipping',
        'country_id' => $country->id,
        'line_one' => '123 ACME Shipping Lane',
        'postcode' => 'AC2 2ME',
        'state' => 'ACM3',
        'contact_phone' => '123456',
    ]);

    assertDatabaseHas(\Payflow\Models\OrderAddress::class, [
        'first_name' => 'Elma',
        'last_name' => 'Thudd',
        'city' => 'ACME Land',
        'type' => 'billing',
        'country_id' => $country->id,
        'line_one' => '123 ACME Lane',
        'postcode' => 'AC1 1ME',
        'state' => 'ACME',
        'contact_email' => 'sales@acme.com',
        'contact_phone' => '1234567',
    ]);
})->group('payflow.stripe.actions');
