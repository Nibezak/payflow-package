<?php

uses(\Payflow\Tests\Core\TestCase::class);

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Payflow\FieldTypes\Text;
use Payflow\Generators\UrlGenerator;
use Payflow\Models\Language;
use Payflow\Models\Product;
use Payflow\Models\Url;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('urls dont generate by default', function () {
    $product = Product::factory()->create();

    expect($product->refresh()->urls)->toHaveCount(0);

    $this->assertDatabaseMissing((new Url)->getTable(), [
        'element_type' => $product->getMorphClass(),
        'element_id' => $product->id,
    ]);
});

/** @test * */
function can_generate_urls()
{
    Language::factory()->create(['default' => true]);

    Config::set('payflow.urls.generator', UrlGenerator::class);

    $product = Product::factory()->create();

    expect($product->refresh()->urls)->toHaveCount(1);

    \Pest\Laravel\assertDatabaseHas((new Url)->getTable(), [
        'element_type' => $product->getMorphClass(),
        'element_id' => $product->id,
        'slug' => Str::slug($product->translateAttribute('name')),
    ]);
}

test('generates unique urls', function () {
    Language::factory()->create(['default' => true]);

    Config::set('payflow.urls.generator', UrlGenerator::class);

    $product1 = Product::factory()->create([
        'attribute_data' => collect([
            'name' => new Text('Test Product'),
        ]),
    ]);

    $product2 = Product::factory()->create([
        'attribute_data' => collect([
            'name' => new Text('Test Product'),
        ]),
    ]);

    $this->assertNotEquals(
        $product1->urls->first()->slug,
        $product2->urls->first()->slug
    );
});
