<?php

uses(\Payflow\Tests\Core\TestCase::class);
use Payflow\Models\Attribute;
use Payflow\Models\AttributeGroup;
use Payflow\Models\ProductType;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can make a product type', function () {
    $productType = ProductType::factory()
        ->has(
            Attribute::factory()->for(AttributeGroup::factory())->count(1),
            'mappedAttributes',
        )
        ->create([
            'name' => 'Bob',
        ]);

    expect($productType->name)->toEqual('Bob');
});
