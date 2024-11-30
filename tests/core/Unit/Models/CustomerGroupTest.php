<?php

uses(\Payflow\Tests\Core\TestCase::class);

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can return discounts', function () {
    $customerGroup = \Payflow\Models\CustomerGroup::factory()->create();

    \Payflow\Models\Discount::factory()->create();

    expect($customerGroup->refresh()->discounts)->toHaveCount(1);
});
