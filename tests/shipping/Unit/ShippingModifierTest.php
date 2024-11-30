<?php

use Payflow\Models\Country;
use Payflow\Models\Currency;
use Payflow\Models\TaxClass;
use Payflow\Shipping\Models\ShippingMethod;
use Payflow\Shipping\Models\ShippingZone;

uses(\Payflow\Tests\Shipping\TestCase::class);
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Payflow\Tests\Shipping\TestUtils::class);

test('can set correct shipping options', function () {
    $currency = Currency::factory()->create([
        'default' => true,
    ]);

    $country = Country::factory()->create();

    TaxClass::factory()->create([
        'default' => true,
    ]);

    $shippingZone = ShippingZone::factory()->create([
        'type' => 'countries',
    ]);

    $shippingZone->countries()->attach($country);

    $shippingMethod = ShippingMethod::factory()->create([
        'driver' => 'ship-by',
        'code' => 'BASEDEL',
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

    $shippingRate = \Payflow\Shipping\Models\ShippingRate::factory()->create([
        'shipping_method_id' => $shippingMethod->id,
        'shipping_zone_id' => $shippingZone->id,
    ]);

    $shippingRate->prices()->createMany([
        [
            'price' => 1000,
            'min_quantity' => 1,
            'currency_id' => $currency->id,
        ],
        [
            'price' => 0,
            'min_quantity' => 500,
            'currency_id' => $currency->id,
        ],
    ]);

    $cart = $this->createCart($currency, 6000, calculate: false);

    $cart->shippingAddress()->create(
        \Payflow\Models\CartAddress::factory()->make([
            'country_id' => $country->id,
            'shipping_option' => 'BASEDEL',
            'state' => null,
            'type' => 'shipping',
        ])->toArray()
    );

    $option = $cart->refresh()->getShippingOption();

    expect($option->price->value)->toBe(0);
})->group('shipping-modifier');
