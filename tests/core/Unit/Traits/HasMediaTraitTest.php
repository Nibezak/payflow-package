<?php

uses(\Payflow\Tests\Core\TestCase::class);
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Payflow\Base\StandardMediaDefinitions;
use Payflow\Models\Product;
use Payflow\Tests\Core\Stubs\TestStandardMediaDefinitions;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('conversions are loaded', function () {
    $definitions = config('payflow.media.definitions');

    expect($definitions)->toHaveCount(6);

    expect($definitions['product'])->toEqual(StandardMediaDefinitions::class);

    $file = UploadedFile::fake()->image('avatar.jpg');

    $product = Product::factory()->create();

    $product->addMedia($file)->toMediaCollection(config('payflow.media.collection'));

    $image = $product->images->first();

    expect($image->hasGeneratedConversion('small'))->toBeTrue();
    expect($image->hasGeneratedConversion('medium'))->toBeTrue();
    expect($image->hasGeneratedConversion('large'))->toBeTrue();
    expect($image->hasGeneratedConversion('zoom'))->toBeTrue();
});

test('custom conversions are loaded', function () {
    Config::set('payflow.media.definitions', [
        'product' => TestStandardMediaDefinitions::class,
    ]);

    $product = invade(new Product);

    expect($product->getDefinitionClass())->toEqual(TestStandardMediaDefinitions::class);
});

test('custom conversions are loaded for extended model', function () {
    \Payflow\Facades\ModelManifest::replace(
        \Payflow\Models\Contracts\Product::class,
        \Payflow\Tests\Core\Stubs\Models\Product::class
    );

    Config::set('payflow.media.definitions', [
        'product' => TestStandardMediaDefinitions::class,
    ]);

    $product = invade(app(\Payflow\Models\Contracts\Product::class));

    expect($product->getDefinitionClass())->toEqual(TestStandardMediaDefinitions::class);
});

test('images can have fallback url', function () {
    $testImageUrl = 'https://picsum.photos/200';
    config()->set('payflow.media.fallback.url', $testImageUrl);

    $product = Product::factory()->create();

    expect($testImageUrl)->toEqual($product->getFirstMediaUrl('images'));
});

test('images can have fallback path', function () {
    $testImagePath = public_path('test.jpg');
    config()->set('payflow.media.fallback.path', $testImagePath);

    $product = Product::factory()->create();

    expect($testImagePath)->toEqual($product->getFirstMediaPath('images'));
});
