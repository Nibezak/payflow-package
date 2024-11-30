<?php

uses(\Payflow\Tests\Core\TestCase::class);
use Illuminate\Support\Facades\Config;
use Payflow\Base\DataTransferObjects\PaymentAuthorize;
use Payflow\Facades\Payments;
use Payflow\Models\Cart;
use Payflow\Models\CartAddress;
use Payflow\Models\Country;
use Payflow\Models\Order;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can authorize payment', function () {
    $cart = Cart::factory()->create();

    Config::set('payflow.payments.types.offline', [
        'authorized' => 'offline-payment',
    ]);

    CartAddress::factory()->create([
        'cart_id' => $cart->id,
        'type' => 'billing',
        'country_id' => Country::factory(),
        'first_name' => 'Santa',
        'line_one' => '123 Elf Road',
        'city' => 'Lapland',
        'postcode' => 'BILL',
    ]);

    CartAddress::factory()->create([
        'cart_id' => $cart->id,
        'type' => 'shipping',
        'country_id' => Country::factory(),
        'first_name' => 'Santa',
        'line_one' => '123 Elf Road',
        'city' => 'Lapland',
        'postcode' => 'SHIPP',
    ]);

    $result = Payments::driver('offline')->cart($cart->refresh())->authorize();

    expect($result)->toBeInstanceOf(PaymentAuthorize::class);
    expect($result->success)->toBeTrue();

    expect($cart->refresh()->completedOrder)->toBeInstanceOf(Order::class);
});

test('can override status', function () {
    $cart = Cart::factory()->create();

    Config::set('payflow.payments.types.offline', [
        'authorized' => 'offline-payment',
    ]);

    CartAddress::factory()->create([
        'cart_id' => $cart->id,
        'type' => 'billing',
        'country_id' => Country::factory(),
        'first_name' => 'Santa',
        'line_one' => '123 Elf Road',
        'city' => 'Lapland',
        'postcode' => 'BILL',
    ]);

    CartAddress::factory()->create([
        'cart_id' => $cart->id,
        'type' => 'shipping',
        'country_id' => Country::factory(),
        'first_name' => 'Santa',
        'line_one' => '123 Elf Road',
        'city' => 'Lapland',
        'postcode' => 'SHIPP',
    ]);

    Payments::driver('offline')->cart($cart->refresh())->withData([
        'authorized' => 'custom-status',
    ])->authorize();

    $order = $cart->refresh()->completedOrder;

    expect($order->status)->toBe('custom-status');
});

test('can set additional meta', function () {
    $cart = Cart::factory()->create();

    Config::set('payflow.payments.types.offline', [
        'authorized' => 'offline-payment',
    ]);

    CartAddress::factory()->create([
        'cart_id' => $cart->id,
        'type' => 'billing',
        'country_id' => Country::factory(),
        'first_name' => 'Santa',
        'line_one' => '123 Elf Road',
        'city' => 'Lapland',
        'postcode' => 'BILL',
    ]);

    CartAddress::factory()->create([
        'cart_id' => $cart->id,
        'type' => 'shipping',
        'country_id' => Country::factory(),
        'first_name' => 'Santa',
        'line_one' => '123 Elf Road',
        'city' => 'Lapland',
        'postcode' => 'SHIPP',
    ]);

    Payments::driver('offline')->cart($cart->refresh())->withData([
        'meta' => [
            'foo' => 'bar',
        ],
    ])->authorize();

    $order = $cart->refresh()->completedOrder;

    $meta = (array) $order->meta;

    expect($meta['foo'])->toEqual('bar');
});
