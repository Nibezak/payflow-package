<?php

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.product');

it('can render product inventory page', function () {
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
        ->get(\Payflow\Admin\Filament\Resources\ProductResource::getUrl('inventory', [
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
            __('payflowpanel::product.pages.inventory.label')
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
            __('payflowpanel::product.pages.inventory.label')
        );
});

it('can update variant stock figures', function () {
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
        \Payflow\Admin\Filament\Resources\ProductResource\Pages\ManageProductInventory::class, [
            'record' => $record->getRouteKey(),
        ])->fillForm([
            'stock' => 500,
            'backorder' => 50,
            'purchasable' => 'in_stock_or_on_backorder',
        ])->call('save')->assertHasNoErrors();

    $this->assertDatabaseHas((new \Payflow\Models\ProductVariant)->getTable(), [
        'stock' => 500,
        'backorder' => 50,
        'purchasable' => 'in_stock_or_on_backorder',
    ]);
});
