<?php

uses(\Payflow\Tests\Core\TestCase::class);

use Payflow\Actions\Carts\UpdateCartLine;
use Payflow\Models\Cart;
use Payflow\Models\CartLine;
use Payflow\Models\Currency;
use Payflow\Models\Price;
use Payflow\Models\ProductVariant;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can update cart line', function () {
    $currency = Currency::factory()->create();

    $cart = Cart::factory()->create([
        'currency_id' => $currency->id,
    ]);

    $purchasable = ProductVariant::factory()->create([
        'stock' => 1,
    ]);

    Price::factory()->create([
        'price' => 100,
        'min_quantity' => 1,
        'currency_id' => $currency->id,
        'priceable_type' => $purchasable->getMorphClass(),
        'priceable_id' => $purchasable->id,
    ]);

    $cart->add($purchasable, 1, ['foo' => 'bar']);

    expect($cart->refresh()->lines)->toHaveCount(1);

    $line = $cart->lines->first();

    $action = new UpdateCartLine;

    $this->assertDatabaseHas((new CartLine)->getTable(), [
        'quantity' => 1,
        'id' => $line->id,
    ]);

    $action->execute($line->id, 2);

    $this->assertDatabaseHas((new CartLine)->getTable(), [
        'quantity' => 2,
        'id' => $line->id,
        'meta' => json_encode(['foo' => 'bar']),
    ]);

    $action->execute($line->id, 2, ['baz' => 'bar']);

    $this->assertDatabaseHas((new CartLine)->getTable(), [
        'quantity' => 2,
        'id' => $line->id,
        'meta' => json_encode(['baz' => 'bar']),
    ]);
});
