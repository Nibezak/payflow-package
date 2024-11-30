<?php

uses(\Payflow\Tests\Core\TestCase::class);
use Payflow\FieldTypes\Text;
use Payflow\Models\Collection;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can make a collection', function () {
    $collection = Collection::factory()
        ->create([
            'attribute_data' => collect([
                'name' => new Text('Red Products'),
            ]),
        ]);

    expect('Red Products')->toEqual($collection->translateAttribute('name'));
});
