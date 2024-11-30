<?php

uses(\Payflow\Tests\Core\TestCase::class);
use Illuminate\Support\Facades\Storage;
use Payflow\Database\State\ConvertTaxbreakdown;
use Payflow\Facades\DB;
use Payflow\Models\Channel;
use Payflow\Models\Currency;
use Payflow\Models\Language;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can run', function () {
    Storage::fake('local');

    Language::factory()->create([
        'default' => true,
    ]);

    $channel = Channel::factory()->create([
        'default' => true,
    ]);

    Currency::factory()->create([
        'code' => 'GBP',
    ]);

    DB::table('payflow_orders')->insert([
        'channel_id' => $channel->id,
        'new_customer' => false,
        'user_id' => null,
        'status' => 'awaiting-payment',
        'reference' => 123123,
        'sub_total' => 400,
        'discount_total' => 0,
        'shipping_total' => 0,
        'tax_breakdown' => '[{"total": 333, "identifier": "tax_rate_1", "percentage": 20, "description": "VAT"}]',
        'tax_total' => 200,
        'total' => 100,
        'notes' => null,
        'currency_code' => 'GBP',
        'compare_currency_code' => 'GBP',
        'exchange_rate' => 1,
        'meta' => '[]',
    ]);

    (new ConvertTaxbreakdown)->run();

    $this->assertDatabaseHas('payflow_orders', [
        'tax_breakdown' => '[{"description":"VAT","identifier":"tax_rate_1","percentage":20,"value":333,"currency_code":"GBP"}]',
    ]);
});
