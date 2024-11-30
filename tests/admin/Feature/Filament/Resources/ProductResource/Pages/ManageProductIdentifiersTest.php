<?php

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.product');

it('can render product identifiers page', function () {
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
        ->get(\Payflow\Admin\Filament\Resources\ProductResource::getUrl('identifiers', [
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
            __('payflowpanel::product.pages.identifiers.label')
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
            __('payflowpanel::relationmanagers.pricing.title')
        );
});

it('can update variant identifiers', function () {
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
        \Payflow\Admin\Filament\Resources\ProductResource\Pages\ManageProductIdentifiers::class, [
            'record' => $record->getRouteKey(),
        ])->fillForm([
            'sku' => 'FOOBARSKU',
            'mpn' => 'FOOBARMPN',
            'gtin' => 'FOOBARGTIN',
            'ean' => 'FOOBAREAN',
        ])->call('save')->assertHasNoErrors();

    $this->assertDatabaseHas((new \Payflow\Models\ProductVariant)->getTable(), [
        'sku' => 'FOOBARSKU',
        'mpn' => 'FOOBARMPN',
        'gtin' => 'FOOBARGTIN',
        'ean' => 'FOOBAREAN',
    ]);
});
