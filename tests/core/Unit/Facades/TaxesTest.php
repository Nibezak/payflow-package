<?php

uses(\Payflow\Tests\Core\TestCase::class);

use Payflow\Base\TaxManagerInterface;
use Payflow\Base\ValueObjects\Cart\TaxBreakdown;
use Payflow\Facades\Taxes;
use Payflow\Models\Currency;
use Payflow\Models\ProductVariant;
use Payflow\Tests\Core\Stubs\TestTaxDriver;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('accessor is correct', function () {
    expect(Taxes::getFacadeAccessor())->toEqual(TaxManagerInterface::class);
});

test('can extend taxes', function () {
    Taxes::extend('testing', function ($app) {
        return $app->make(TestTaxDriver::class);
    });

    expect(Taxes::driver('testing'))->toBeInstanceOf(TestTaxDriver::class);

    $result = Taxes::driver('testing')->setPurchasable(
        ProductVariant::factory()->create()
    )->setCurrency(
        Currency::factory()->create()
    )->getBreakdown(123);

    expect($result)->toBeInstanceOf(TaxBreakdown::class);
});
