<?php

uses(\Payflow\Tests\Shipping\TestCase::class);

use Payflow\DataTypes\ShippingOption;
use Payflow\Models\Currency;
use Payflow\Models\TaxClass;
use Payflow\Shipping\DataTransferObjects\ShippingOptionRequest;
use Payflow\Shipping\Drivers\ShippingMethods\Collection;
use Payflow\Shipping\Models\ShippingMethod;
use Payflow\Shipping\Models\ShippingZone;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Payflow\Tests\Shipping\TestUtils::class);

test('can get free shipping', function () {
    $currency = Currency::factory()->create([
        'default' => true,
    ]);

    TaxClass::factory()->create([
        'default' => true,
    ]);

    $shippingZone = ShippingZone::factory()->create([
        'type' => 'countries',
    ]);

    $shippingMethod = ShippingMethod::factory()->create([
        'driver' => 'free-shipping',
        'data' => [],
    ]);

    $shippingRate = \Payflow\Shipping\Models\ShippingRate::factory()->create([
        'shipping_method_id' => $shippingMethod->id,
        'shipping_zone_id' => $shippingZone->id,
    ]);

    $cart = $this->createCart($currency, 500);

    $driver = new Collection;

    $request = new ShippingOptionRequest(
        cart: $cart,
        shippingRate: $shippingRate
    );

    $shippingOption = $driver->resolve($request);

    expect($shippingOption)->toBeInstanceOf(ShippingOption::class);

    expect($shippingOption->price->value)->toEqual(0);
});
