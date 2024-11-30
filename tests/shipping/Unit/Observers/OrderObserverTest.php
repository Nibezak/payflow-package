<?php

use Payflow\Models\Order;
use Payflow\Shipping\Observers\OrderObserver;

uses(\Payflow\Tests\Shipping\TestCase::class);
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Payflow\Tests\Shipping\TestUtils::class);

test('can store shipping zone against order', function () {

    Order::observe(OrderObserver::class);

    $currency = \Payflow\Models\Currency::factory()->create([
        'default' => true,
    ]);

    $country = \Payflow\Models\Country::factory()->create();

    \Payflow\Models\TaxClass::factory()->create([
        'default' => true,
    ]);

    $shippingZone = \Payflow\Shipping\Models\ShippingZone::factory()->create([
        'type' => 'countries',
    ]);

    $shippingZone->countries()->attach($country);

    $shippingMethod = \Payflow\Shipping\Models\ShippingMethod::factory()->create([
        'driver' => 'ship-by',
        'data' => [
            'minimum_spend' => [
                "{$currency->code}" => 200,
            ],
        ],
    ]);

    $customerGroup = \Payflow\Models\CustomerGroup::factory()->create([
        'default' => true,
    ]);
    $shippingMethod->customerGroups()->sync([
        $customerGroup->id => ['enabled' => true, 'visible' => true, 'starts_at' => now(), 'ends_at' => null],
    ]);

    $shippingRate = \Payflow\Shipping\Models\ShippingRate::factory()
        ->create([
            'shipping_method_id' => $shippingMethod->id,
            'shipping_zone_id' => $shippingZone->id,
        ]);

    $shippingRate->prices()->createMany([
        [
            'price' => 600,
            'min_quantity' => 1,
            'currency_id' => $currency->id,
        ],
        [
            'price' => 500,
            'min_quantity' => 700,
            'currency_id' => $currency->id,
        ],
        [
            'price' => 0,
            'min_quantity' => 800,
            'currency_id' => $currency->id,
        ],
    ]);

    $cart = $this->createCart($currency, 500);

    $cart->shippingAddress()->create(
        \Payflow\Models\CartAddress::factory()->make([
            'country_id' => $country->id,
            'state' => null,
        ])->toArray()
    );

    $cart->billingAddress()->create(
        \Payflow\Models\CartAddress::factory()->make([
            'country_id' => $country->id,
            'type' => 'billing',
            'state' => null,
        ])->toArray()
    );

    $shippingOption = \Payflow\Facades\ShippingManifest::getOptions($cart->refresh())->first();

    $cart->setShippingOption($shippingOption);

    $order = $cart->refresh()->createOrder();
    $orderShippingZone = $order->shippingZone->first();

    expect($orderShippingZone)->toBeInstanceOf(\Payflow\Shipping\Models\ShippingZone::class)
        ->and($orderShippingZone->id)
        ->toBe($shippingZone->id);
});
