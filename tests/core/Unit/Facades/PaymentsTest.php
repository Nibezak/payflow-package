<?php

uses(\Payflow\Tests\Core\TestCase::class);

use Payflow\Base\DataTransferObjects\PaymentAuthorize;
use Payflow\Base\PaymentManagerInterface;
use Payflow\Facades\Payments;
use Payflow\Tests\Core\Stubs\TestPaymentDriver;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('accessor is correct', function () {
    expect(Payments::getFacadeAccessor())->toEqual(PaymentManagerInterface::class);
});

test('can extend payments', function () {
    Payments::extend('testing', function ($app) {
        return $app->make(TestPaymentDriver::class);
    });

    expect(Payments::driver('testing'))->toBeInstanceOf(TestPaymentDriver::class);

    $result = Payments::driver('testing')->authorize();

    expect($result)->toBeInstanceOf(PaymentAuthorize::class);
});
