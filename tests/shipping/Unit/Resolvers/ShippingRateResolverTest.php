<?php

uses(\Payflow\Tests\Shipping\TestCase::class);

use Payflow\Models\CartAddress;
use Payflow\Models\Country;
use Payflow\Models\Currency;
use Payflow\Models\Price;
use Payflow\Models\ProductVariant;
use Payflow\Models\State;
use Payflow\Models\TaxClass;
use Payflow\Shipping\Facades\Shipping;
use Payflow\Shipping\Models\ShippingMethod;
use Payflow\Shipping\Models\ShippingZone;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Payflow\Tests\Shipping\TestUtils::class);

test('can fetch shipping rates by country', function () {
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

    $shippingRate = \Payflow\Shipping\Models\ShippingRate::factory()->create([
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
    expect($shippingRates->first()->id)->toEqual($shippingRate->id);

    $cart = $this->createCart($currency, 500);

    $secondCountry = Country::factory()->create();

    $cart->shippingAddress()->create(
        CartAddress::factory()->make([
            'country_id' => $secondCountry->id,
            'state' => null,
        ])->toArray()
    );

    $shippingRates = Shipping::shippingRates(
        $cart->refresh()->calculate()
    )->get();

    expect($shippingRates)->toBeEmpty();
});

test('can fetch shipping rates by state', function () {
    $currency = Currency::factory()->create([
        'default' => true,
    ]);

    $country = Country::factory()->create();

    TaxClass::factory()->create([
        'default' => true,
    ]);

    $shippingZone = ShippingZone::factory()->create([
        'type' => 'states',
    ]);

    $state = State::factory()->create([
        'country_id' => $country->id,
    ]);

    $shippingZone->states()->attach($state);

    $shippingMethod = ShippingMethod::factory()->create([
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

    $shippingRate = \Payflow\Shipping\Models\ShippingRate::factory()->create([
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
            'state' => $state->name,
        ])->toArray()
    );

    $shippingRates = Shipping::shippingRates(
        $cart->refresh()->calculate()
    )->get();

    expect($shippingRates)->toHaveCount(1);
    expect($shippingRates->first()->id)->toEqual($shippingRate->id);
});

test('can fetch shipping rates by postcode', function () {
    $currency = Currency::factory()->create([
        'default' => true,
    ]);

    $country = Country::factory()->create();

    TaxClass::factory()->create([
        'default' => true,
    ]);

    $shippingZone = ShippingZone::factory()->create([
        'type' => 'postcodes',
    ]);

    $shippingZone->postcodes()->create([
        'postcode' => 'AB1',
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
            'postcode' => 'AB1 1CD',
        ])->toArray()
    );

    $shippingRates = Shipping::shippingRates(
        $cart->refresh()->calculate()
    )->get();

    expect($shippingRates)->toHaveCount(1);
    expect($shippingRates->first()->id)->toEqual($shippingRate->id);
});

test('can reject shipping rates when stock is not available', function () {
    $currency = Currency::factory()->create([
        'default' => true,
    ]);

    $country = Country::factory()->create();

    TaxClass::factory()->create([
        'default' => true,
    ]);

    $shippingZone = ShippingZone::factory()->create([
        'type' => 'postcodes',
    ]);

    $shippingZone->postcodes()->create([
        'postcode' => 'AB1',
    ]);

    $shippingZone->countries()->attach($country);

    $shippingMethod = ShippingMethod::factory()->create([
        'driver' => 'ship-by',
        'data' => [
            'minimum_spend' => [
                "{$currency->code}" => 200,
            ],
        ],
        'stock_available' => 1,
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

    $purchasable = ProductVariant::factory()->create();
    $purchasable->stock = 0;

    Price::factory()->create([
        'price' => 200,
        'min_quantity' => 1,
        'currency_id' => $currency->id,
        'priceable_type' => $purchasable->getMorphClass(),
        'priceable_id' => $purchasable->id,
    ]);

    $cart->lines()->create([
        'purchasable_type' => $purchasable->getMorphClass(),
        'purchasable_id' => $purchasable->id,
        'quantity' => 1,
    ]);

    $cart->shippingAddress()->create(
        CartAddress::factory()->make([
            'country_id' => $country->id,
            'state' => null,
            'postcode' => 'AB1 1CD',
        ])->toArray()
    );

    $shippingRates = Shipping::shippingRates(
        $cart->refresh()->calculate()
    )->get();

    expect($shippingRates)->toHaveCount(0);
});
