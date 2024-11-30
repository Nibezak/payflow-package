<?php

uses(\Payflow\Tests\Shipping\TestCase::class);

use Payflow\Models\CartAddress;
use Payflow\Models\Country;
use Payflow\Models\Currency;
use Payflow\Models\TaxClass;
use Payflow\Shipping\Facades\Shipping;
use Payflow\Shipping\Models\ShippingMethod;
use Payflow\Shipping\Models\ShippingZone;
use Payflow\Shipping\Resolvers\ShippingZoneResolver;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Payflow\Tests\Shipping\TestUtils::class);

test('zones method uses shipping zone resolver', function () {
    $resolver = Shipping::zones();
    expect($resolver)->toBeInstanceOf(ShippingZoneResolver::class);
});

test('can fetch expected shipping rates', function () {
    $currency = Currency::factory()->create([
        'default' => true,
    ]);

    $country = Country::factory()->create();

    TaxClass::factory()->create([
        'default' => true,
    ]);

    $customerGroup = \Payflow\Models\CustomerGroup::factory()->create([
        'default' => true,
    ]);

    $shippingZone = ShippingZone::factory()->create([
        'type' => 'countries',
    ]);

    $shippingZone->countries()->attach($country);

    $shippingMethod = ShippingMethod::factory()->create([
        'driver' => 'ship-by',
        'data' => [
            'minimum_spend' => [
                "{$currency->code}" => 200,
            ],
        ],
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
        CartAddress::factory()->make([
            'country_id' => $country->id,
            'state' => null,
        ])->toArray()
    );

    $shippingRates = Shipping::shippingRates(
        $cart->refresh()->calculate()
    )->get();

    expect($shippingRates)->toHaveCount(1);
});
