<?php

use Livewire\Livewire;
use Payflow\Admin\Filament\Resources\ProductTypeResource;
use Payflow\Models\ProductType;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.productType');

it('can render product type create page', function () {
    $this->asStaff(admin: true)
        ->get(ProductTypeResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create product type', function () {
    $productType = ProductType::factory()->make();

    $formData = [
        'name' => $productType->name,
    ];

    Livewire::actingAs($this->makeStaff(admin: true), 'staff')
        ->test(ProductTypeResource\Pages\CreateProductType::class)
        ->fillForm($formData)
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(ProductType::class, $formData);
});

it('can associate attributes', function () {
    $productType = ProductType::factory()->make();

    $attributeA = \Payflow\Models\Attribute::factory()->create([
        'attribute_type' => 'product',
    ]);

    $attributeB = \Payflow\Models\Attribute::factory()->create([
        'attribute_type' => 'product',
    ]);

    $formData = [
        'name' => $productType->name,
    ];

    $component = Livewire::actingAs($this->makeStaff(admin: true), 'staff')
        ->test(ProductTypeResource\Pages\CreateProductType::class)
        ->fillForm([
            ...$formData,
            'mappedAttributes' => [$attributeA->id, $attributeB->id],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas((new ProductType)->mappedAttributes()->getTable(), [
        'attributable_type' => ProductType::morphName(),
        'attributable_id' => $component->get('record')->id,
    ]);
});
