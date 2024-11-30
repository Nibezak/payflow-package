<?php

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.product');

it('can render product shipping page', function () {
    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    \Payflow\Models\Currency::factory()->create([
        'default' => true,
    ]);

    $record = \Payflow\Models\Product::factory()->create();

    \Payflow\Models\ProductVariant::factory()->create([
        'product_id' => $record->id,
    ]);

    $this->asStaff(admin: true)
        ->get(\Payflow\Admin\Filament\Resources\ProductResource::getUrl('shipping', [
            'record' => $record,
        ]))
        ->assertSuccessful();
});

it('will show in navigation when only one variant exists', function () {
    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    \Payflow\Models\Currency::factory()->create([
        'default' => true,
    ]);

    $record = \Payflow\Models\Product::factory()->create();

    \Payflow\Models\ProductVariant::factory()->create([
        'product_id' => $record->id,
    ]);

    $this->asStaff(admin: true)
        ->get(\Payflow\Admin\Filament\Resources\ProductResource::getUrl('edit', [
            'record' => $record,
        ]))
        ->assertSuccessful()
        ->assertSeeText(
            __('payflowpanel::product.pages.shipping.label')
        );
});

it('will not show in navigation when multiple variants exist', function () {
    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    \Payflow\Models\Currency::factory()->create([
        'default' => true,
    ]);

    $record = \Payflow\Models\Product::factory()->create();

    \Payflow\Models\ProductVariant::factory(2)->create([
        'product_id' => $record->id,
    ]);

    $this->asStaff(admin: true)
        ->get(\Payflow\Admin\Filament\Resources\ProductResource::getUrl('edit', [
            'record' => $record,
        ]))
        ->assertSuccessful()
        ->assertDontSeeText(
            __('payflowpanel::product.pages.shipping.label')
        );
});

it('can update variant shipping', function () {
    $language = \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $currency = \Payflow\Models\Currency::factory()->create([
        'default' => true,
        'decimal_places' => 2,
    ]);

    $record = \Payflow\Models\Product::factory()->create();

    $variant = \Payflow\Models\ProductVariant::factory()->create([
        'product_id' => $record->id,
    ]);

    $this->asStaff();

    \Livewire\Livewire::test(
        \Payflow\Admin\Filament\Resources\ProductResource\Pages\ManageProductShipping::class, [
            'record' => $record->getRouteKey(),
        ])->fillForm([
            'shippable' => true,
            'dimensions.length_value' => 100,
            'dimensions.length_unit' => 'cm',
            'dimensions.width_value' => 200,
            'dimensions.width_unit' => 'm',
            'dimensions.height_value' => 10,
            'dimensions.height_unit' => 'cm',
            'dimensions.weight_value' => 10,
            'dimensions.weight_unit' => 'g',
        ])->call('save')->assertHasNoErrors();

    $this->assertDatabaseHas((new \Payflow\Models\ProductVariant)->getTable(), [
        'shippable' => true,
        'length_value' => 100,
        'length_unit' => 'cm',
        'width_value' => 200,
        'width_unit' => 'm',
        'height_value' => 10,
        'height_unit' => 'cm',
        'weight_value' => 10,
        'weight_unit' => 'g',
    ]);
});

it('can set shipping volume automatically', function () {
    $language = \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $currency = \Payflow\Models\Currency::factory()->create([
        'default' => true,
        'decimal_places' => 2,
    ]);

    $record = \Payflow\Models\Product::factory()->create();

    $variant = \Payflow\Models\ProductVariant::factory()->create([
        'product_id' => $record->id,
    ]);

    $this->asStaff();

    \Livewire\Livewire::test(
        \Payflow\Admin\Filament\Resources\ProductResource\Pages\ManageProductShipping::class, [
            'record' => $record->getRouteKey(),
        ])->fillForm([
            'shippable' => true,
            'dimensions.length_value' => 100,
            'dimensions.length_unit' => 'cm',
            'dimensions.width_value' => 100,
            'dimensions.width_unit' => 'cm',
            'dimensions.height_value' => 100,
            'dimensions.height_unit' => 'cm',
            'dimensions.weight_value' => 10,
            'dimensions.weight_unit' => 'g',
        ])->call('save')->assertHasNoErrors();

    $this->assertDatabaseHas((new \Payflow\Models\ProductVariant)->getTable(), [
        'volume_value' => 1000,
        'volume_unit' => 'l',
    ]);
});
