<?php

namespace Payflow\Tests\Shipping;

use Payflow\Models\Cart;
use Payflow\Models\Currency;
use Payflow\Models\Price;
use Payflow\Models\ProductVariant;

trait TestUtils
{
    public function createCart($currency = null, $price = 100, $quantity = 1, $calculate = true)
    {
        if (! $currency) {
            $currency = Currency::factory()->create([
                'default' => true,
            ]);
        }

        $cart = Cart::factory()->create([
            'currency_id' => $currency->id,
        ]);

        $purchasable = ProductVariant::factory()->create();
        $purchasable->stock = 100;

        Price::factory()->create([
            'price' => $price,
            'min_quantity' => 1,
            'currency_id' => $currency->id,
            'priceable_type' => $purchasable->getMorphClass(),
            'priceable_id' => $purchasable->id,
        ]);

        $cart->lines()->create([
            'purchasable_type' => $purchasable->getMorphClass(),
            'purchasable_id' => $purchasable->id,
            'quantity' => $quantity,
        ]);

        expect($cart->total)->toBeNull()
            ->and($cart->taxTotal)->toBeNull()
            ->and($cart->subTotal)->toBeNull();

        return $calculate ? $cart->calculate() : $cart;
    }
}
