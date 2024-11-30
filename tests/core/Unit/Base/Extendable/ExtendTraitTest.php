<?php

uses(\Payflow\Tests\Core\Unit\Base\Extendable\ExtendableTestCase::class);

use Payflow\Models\Product;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(
    fn () => \Payflow\Facades\ModelManifest::replace(
        \Payflow\Models\Contracts\Product::class,
        \Payflow\Tests\Core\Stubs\Models\Product::class
    )
);

test('can override scout should be searchable method', function () {

    $product = Product::find(1);
    expect($product->shouldBeSearchable())->toBeFalse();
});
