<?php

uses(\Payflow\Tests\Core\TestCase::class);

use Payflow\Models\Cart;
use Payflow\Models\Currency;
use Payflow\Models\Order;
use Payflow\Models\Price;
use Payflow\Models\ProductVariant;
use Payflow\Pipelines\Order\Creation\CreateOrderLines;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can run pipeline', function () {
    $currency = Currency::factory()->create();

    $cart = Cart::factory()->create([
        'currency_id' => $currency->id,
    ]);

    $purchasable = ProductVariant::factory()->create();

    Price::factory()->create([
        'price' => 100,
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

    $order = Order::factory()->create([
        'cart_id' => $cart->id,
    ]);

    $cart->recalculate();

    app(CreateOrderLines::class)->handle($order, function ($order) {
        return $order;
    });

    expect($order->lines)->toHaveCount($cart->lines->count());
});
