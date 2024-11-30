<?php

uses(\Payflow\Tests\Core\TestCase::class)->group('model_extending');

use Payflow\Base\ModelManifestInterface;
use Payflow\Facades\ModelManifest;
use Payflow\Models\Product;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can instantiate class', function () {
    $manifest = app(ModelManifestInterface::class);

    expect($manifest)->toBeInstanceOf(\Payflow\Base\ModelManifest::class);
});

test('can add model', function () {
    ModelManifest::add(
        \Payflow\Models\Contracts\Product::class,
        \Payflow\Tests\Core\Stubs\Models\Product::class,
    );

    expect(Product::modelClass())->toBe(\Payflow\Tests\Core\Stubs\Models\Product::class);
});

test('can replace model', function () {
    ModelManifest::replace(
        \Payflow\Models\Contracts\Product::class,
        \Payflow\Tests\Core\Stubs\Models\Product::class,
    );

    expect(Product::modelClass())->toBe(\Payflow\Tests\Core\Stubs\Models\Product::class);
});

test('can get registered model', function () {
    expect(
        ModelManifest::get(\Payflow\Models\Contracts\Product::class)
    )->toBe(Product::class);

    ModelManifest::replace(
        \Payflow\Models\Contracts\Product::class,
        \Payflow\Tests\Core\Stubs\Models\Product::class,
    );

    expect(
        ModelManifest::get(\Payflow\Models\Contracts\Product::class)
    )->toBe(\Payflow\Tests\Core\Stubs\Models\Product::class);
});

test('can guess contract class', function () {
    expect(
        ModelManifest::guessContractClass(Product::class)
    )->toBe(\Payflow\Models\Contracts\Product::class);
});

test('can guess model class', function () {
    expect(
        ModelManifest::guessModelClass(\Payflow\Models\Contracts\Product::class)
    )->toBe(Product::class);
});

test('can detect payflow model', function () {
    expect(
        ModelManifest::isPayflowModel((new Product))
    )->toBeTrue()
        ->and(
            ModelManifest::isPayflowModel((new \Payflow\Tests\Core\Stubs\Models\Product))
        )->toBeFalse();
});
