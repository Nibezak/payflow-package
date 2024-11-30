<?php

uses(\Payflow\Tests\Core\TestCase::class);

use Payflow\Models\ProductOption;
use Payflow\Search\ProductOptionIndexer;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can return correct searchable data', function () {
    $productOption = ProductOption::factory()->create();

    $data = app(ProductOptionIndexer::class)->toSearchableArray($productOption);

    expect($data['name_en'])->toEqual($productOption->name->en)
        ->and($data['label_en'])->toEqual($productOption->label->en);
});
