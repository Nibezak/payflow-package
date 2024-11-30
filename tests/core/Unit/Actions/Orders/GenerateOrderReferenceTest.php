<?php

uses(\Payflow\Tests\Core\TestCase::class);

use Illuminate\Support\Facades\Config;
use Payflow\Actions\Orders\GenerateOrderReference;
use Payflow\Models\Currency;
use Payflow\Models\Language;
use Payflow\Models\Order;
use Payflow\Tests\Core\Stubs\TestOrderReferenceGenerator;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    Language::factory()->create([
        'default' => true,
        'code' => 'en',
    ]);

    Currency::factory()->create([
        'default' => true,
        'decimal_places' => 2,
    ]);
});

test('can generate reference', function () {
    $order = Order::factory()->create([
        'reference' => null,
        'placed_at' => now(),
    ]);

    expect($order->reference)->toBeNull();

    $result = app(GenerateOrderReference::class)->execute($order);

    expect($result)->toEqual($order->created_at->format('Y-m').'-0001');
});

test('can override generator via config', function () {
    $order = Order::factory()->create([
        'reference' => null,
        'placed_at' => now(),
    ]);

    Config::set('payflow.orders.reference_generator', TestOrderReferenceGenerator::class);

    expect($order->reference)->toBeNull();

    $result = app(GenerateOrderReference::class)->execute($order);

    expect($result)->toEqual('reference-'.$order->id);
});

test('can set generator to null', function () {
    $order = Order::factory()->create([
        'reference' => null,
        'placed_at' => now(),
    ]);

    Config::set('payflow.orders.reference_generator', null);

    expect($order->reference)->toBeNull();

    $result = app(GenerateOrderReference::class)->execute($order);

    expect($result)->toBeNull();
});
