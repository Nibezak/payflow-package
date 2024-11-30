<?php

uses(\Payflow\Tests\Core\TestCase::class);
use Payflow\Base\Casts\ShippingBreakdown as ShippingBreakdownCasts;
use Payflow\Base\ValueObjects\Cart\ShippingBreakdown;
use Payflow\Base\ValueObjects\Cart\ShippingBreakdownItem;
use Payflow\DataTypes\Price;
use Payflow\Models\Currency;
use Payflow\Models\Order;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can set from value object', function () {
    $currency = Currency::factory()->create();
    $order = Order::factory()->create();

    $shippingBreakdownValueObject = new ShippingBreakdown;

    $shippingBreakdownValueObject->items->put('DELIV',
        new ShippingBreakdownItem(
            name: 'Basic Delivery',
            identifier: 'DELIV',
            price: new Price(700, $currency, 1),
        )
    );

    $breakDown = new ShippingBreakdownCasts;

    $result = $breakDown->set($order, 'shipping_breakdown', $shippingBreakdownValueObject, []);

    expect($result)->toHaveKey('shipping_breakdown');
    expect($result['shipping_breakdown'])->toBeJson();
});

test('can cast to and from model', function () {
    $currency = Currency::factory()->create();
    $order = Order::factory()->create();

    $shippingBreakdownValueObject = new ShippingBreakdown;

    $shippingBreakdownValueObject->items->put('DELIV',
        new ShippingBreakdownItem(
            name: 'Basic Delivery',
            identifier: 'DELIV',
            price: new Price(700, $currency, 1),
        )
    );

    $order->update([
        'shipping_breakdown' => $shippingBreakdownValueObject,
    ]);

    $breakdown = $order->refresh()->shipping_breakdown;
    expect($breakdown)->toBeInstanceOf(ShippingBreakdown::class);
});
