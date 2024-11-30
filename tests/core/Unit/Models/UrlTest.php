<?php

uses(\Payflow\Tests\Core\TestCase::class);

use Illuminate\Support\Str;
use Payflow\Models\Language;
use Payflow\Models\Product;
use Payflow\Models\Url;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can create a url', function () {
    $product = Product::factory()->create();
    $language = Language::factory()->create();

    $data = [
        'language_id' => $language->id,
        'element_id' => $product->id,
        'element_type' => $product->getMorphClass(),
        'slug' => Str::slug($product->translateAttribute('name')),
        'default' => true,
    ];

    Url::create($data);

    $this->assertDatabaseHas('payflow_urls', $data);
});

test('can fetch element from url relationship', function () {
    $product = Product::factory()->create();
    $language = Language::factory()->create();

    $data = [
        'language_id' => $language->id,
        'element_id' => $product->id,
        'element_type' => $product->getMorphClass(),
        'slug' => Str::slug($product->translateAttribute('name')),
        'default' => true,
    ];

    $url = Url::create($data);

    expect($url->element)->toBeInstanceOf(Product::class);
    expect($url->element->id)->toEqual($product->id);
});
