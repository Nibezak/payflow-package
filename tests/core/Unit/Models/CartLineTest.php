<?php

uses(\Payflow\Tests\Core\TestCase::class);

use Payflow\Exceptions\NonPurchasableItemException;
use Payflow\Models\Cart;
use Payflow\Models\CartLine;
use Payflow\Models\Channel;
use Payflow\Models\ProductVariant;
use Payflow\Tests\Core\Stubs\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can make a cart line', function () {
    $cart = Cart::factory()->create([
        'user_id' => User::factory(),
    ]);

    $variant = ProductVariant::factory()->create();

    $data = [
        'cart_id' => $cart->id,
        'quantity' => 1,
        'purchasable_type' => $variant->getMorphClass(),
        'purchasable_id' => $variant->id,
    ];

    CartLine::create($data);

    $this->assertDatabaseHas((new CartLine)->getTable(), $data);
});

test('only purchasables can be added to a cart', function () {
    $cart = Cart::factory()->create([
        'user_id' => User::factory(),
    ]);

    $channel = Channel::factory()->create();

    $data = [
        'cart_id' => $cart->id,
        'quantity' => 1,
        'purchasable_type' => $channel->getMorphClass(),
        'purchasable_id' => $channel->id,
    ];

    $this->expectException(NonPurchasableItemException::class);

    CartLine::create($data);

    $this->assertDatabaseMissing((new CartLine)->getTable(), $data);
});
