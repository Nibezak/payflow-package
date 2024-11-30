<?php

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.product');

it('can render product prices create page', function () {
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
        ->get(\Payflow\Admin\Filament\Resources\ProductResource::getUrl('pricing', [
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
            __('payflowpanel::relationmanagers.pricing.title')
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
        ->get(\Payflow\Admin\Filament\Resources\ProductResource::getUrl('index', [
            'record' => $record,
        ]))
        ->assertSuccessful()
        ->assertDontSeeText(
            __('payflowpanel::relationmanagers.pricing.title')
        );
});
