<?php

uses(\Payflow\Tests\Core\Unit\Base\Extendable\ExtendableTestCase::class)->group('model_extending');

use Illuminate\Support\Collection;
use Payflow\Models\Product;
use Payflow\Models\ProductOption;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(
    function () {
        \Payflow\Facades\ModelManifest::replace(
            \Payflow\Models\Contracts\Product::class,
            \Payflow\Tests\Core\Stubs\Models\Product::class
        );

        \Payflow\Facades\ModelManifest::replace(
            \Payflow\Models\Contracts\ProductOption::class,
            \Payflow\Tests\Core\Stubs\Models\ProductOption::class
        );
    }
);

test('can get new instance of the registered model', function () {
    $product = Product::find(1);

    expect($product)->toBeInstanceOf(\Payflow\Tests\Core\Stubs\Models\Product::class);
});

test('can forward calls to extended model', function () {
    // @phpstan-ignore-next-line
    $sizeOption = ProductOption::with('sizes')->find(1);

    expect($sizeOption)->toBeInstanceOf(\Payflow\Tests\Core\Stubs\Models\ProductOption::class);

    expect($sizeOption->sizes)->toBeInstanceOf(Collection::class);
    expect($sizeOption->sizes)->toHaveCount(1);
});

test('extended model returns correct table name', function () {
    expect((new \Payflow\Tests\Core\Stubs\Models\CustomOrder)->getTable())
        ->toBe(
            (new \Payflow\Models\Order)->getTable()
        );
});

test('can forward static method calls to extended model', function () {
    /** @see \Payflow\Tests\Core\Stubs\Models\ProductOption::getSizesStatic() */
    $newStaticMethod = ProductOption::getSizesStatic();

    expect($newStaticMethod)->toBeInstanceOf(Collection::class);
    expect($newStaticMethod)->toHaveCount(3);
});

test('morph map is correct when models are extended', function () {
    \Payflow\Facades\ModelManifest::replace(
        \Payflow\Models\Contracts\Product::class,
        \Payflow\Tests\Core\Stubs\Models\CustomProduct::class
    );

    expect((new \Payflow\Tests\Core\Stubs\Models\CustomProduct)->getMorphClass())
        ->toBe('product')
        ->and(\Payflow\Tests\Core\Stubs\Models\CustomProduct::morphName())
        ->toBe('product')
        ->and((new Product)->getMorphClass())
        ->toBe('product')
        ->and(Product::morphName())
        ->toBe('product');
});
