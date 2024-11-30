<?php

uses(\Payflow\Tests\Core\TestCase::class);
use Payflow\Models\Currency;
use Payflow\Models\Order;
use Payflow\Search\OrderIndexer;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can return correct searchable data', function () {
    Currency::factory()->create([
        'code' => 'GBP',
        'default' => true,
    ]);

    $order = Order::factory()->create([
        'user_id' => null,
        'placed_at' => now(),
        'meta' => [
            'foo' => 'bar',
        ],
    ]);

    $data = app(OrderIndexer::class)->toSearchableArray($order);

    expect($data['currency_code'])->toEqual('GBP');
    expect($data['channel'])->toEqual($order->channel->name);
    expect($data['total'])->toEqual($order->total->value);
});
