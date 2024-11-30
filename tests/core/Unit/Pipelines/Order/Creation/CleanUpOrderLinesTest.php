<?php

uses(\Payflow\Tests\Core\TestCase::class);

use Payflow\DataTypes\Price;
use Payflow\DataTypes\ShippingOption;
use Payflow\Facades\ShippingManifest;
use Payflow\Models\Cart;
use Payflow\Models\CartAddress;
use Payflow\Models\Currency;
use Payflow\Models\Order;
use Payflow\Models\OrderLine;
use Payflow\Models\ProductVariant;
use Payflow\Models\TaxClass;
use Payflow\Pipelines\Order\Creation\CleanUpOrderLines;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can run pipeline', function () {
    $currency = Currency::factory()->create();

    $cart = Cart::factory()->create([
        'currency_id' => $currency->id,
    ]);

    ShippingManifest::addOption(
        new ShippingOption(
            name: 'Basic Delivery',
            description: 'Basic Delivery',
            identifier: 'BASDEL',
            price: new Price(500, $cart->currency, 1),
            taxClass: TaxClass::factory()->create()
        )
    );

    CartAddress::factory()->create([
        'type' => 'shipping',
        'shipping_option' => 'BASDEL',
        'cart_id' => $cart->id,
    ]);

    $order = Order::factory()->create([
        'cart_id' => $cart->id,
    ]);

    $purchasable = ProductVariant::factory()->create();
    $purchasableB = ProductVariant::factory()->create();

    \Payflow\Models\Price::factory()->create([
        'price' => 100,
        'min_quantity' => 1,
        'currency_id' => $currency->id,
        'priceable_type' => $purchasable->getMorphClass(),
        'priceable_id' => $purchasable->id,
    ]);

    \Payflow\Models\Price::factory()->create([
        'price' => 100,
        'min_quantity' => 1,
        'currency_id' => $currency->id,
        'priceable_type' => $purchasableB->getMorphClass(),
        'priceable_id' => $purchasableB->id,
    ]);

    $cart->lines()->create([
        'purchasable_type' => $purchasable->getMorphClass(),
        'purchasable_id' => $purchasable->id,
        'quantity' => 1,
    ]);

    OrderLine::factory()->create([
        'order_id' => $order->id,
        'purchasable_id' => $purchasable->id,
        'purchasable_type' => $purchasable->getMorphClass(),
    ]);

    OrderLine::factory()->create([
        'order_id' => $order->id,
        'purchasable_id' => $purchasableB->id,
        'purchasable_type' => $purchasableB->getMorphClass(),
    ]);

    OrderLine::factory()->create([
        'identifier' => 'BASDEL',
        'purchasable_type' => ShippingOption::class,
        'type' => 'shipping',
        'order_id' => $order->id,
    ]);

    $order = app(CleanUpOrderLines::class)->handle($order, function ($order) {
        return $order;
    });

    expect($order->productLines)->toHaveCount(1);
    expect($order->shippingLines->first()->identifier)->toEqual('BASDEL');
});
