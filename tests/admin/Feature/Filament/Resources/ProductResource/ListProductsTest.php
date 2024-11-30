<?php

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.product');

it('can create product', function () {
    \Payflow\Models\Attribute::factory()->create([
        'type' => \Payflow\FieldTypes\TranslatedText::class,
        'attribute_type' => 'product',
        'handle' => 'name',
        'name' => [
            'en' => 'Name',
        ],
        'description' => [
            'en' => 'Description',
        ],
    ]);
    \Payflow\Models\TaxClass::factory()->create([
        'default' => true,
    ]);
    \Payflow\Models\Currency::factory()->create([
        'default' => true,
        'decimal_places' => 2,
    ]);
    $language = \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $productType = \Payflow\Models\ProductType::factory()->create();

    $this->asStaff();

    \Livewire\Livewire::test(\Payflow\Admin\Filament\Resources\ProductResource\Pages\ListProducts::class)
        ->callAction('create', data: [
            'name' => [$language->code => 'Foo Bar'],
            'base_price' => 10.99,
            'sku' => 'ABCABCAB',
            'product_type_id' => $productType->id,
        ])->assertHasNoActionErrors();

    \Pest\Laravel\assertDatabaseHas((new \Payflow\Models\Product)->getTable(), [
        'product_type_id' => $productType->id,
        'status' => 'draft',
        'attribute_data' => json_encode([
            'name' => [
                'field_type' => \Payflow\FieldTypes\TranslatedText::class,
                'value' => [
                    $language->code => 'Foo Bar',
                ],
            ],
        ]),
    ]);

    $this->assertDatabaseHas((new \Payflow\Models\ProductVariant)->getTable(), [
        'sku' => 'ABCABCAB',
    ]);

    $this->assertDatabaseHas((new \Payflow\Models\Price)->getTable(), [
        'price' => '1099',
    ]);
});
