<?php

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.product');

it('can render product urls create page', function () {
    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $record = \Payflow\Models\Product::factory()->create();

    $this->asStaff(admin: true)
        ->get(\Payflow\Admin\Filament\Resources\ProductResource::getUrl('urls', [
            'record' => $record,
        ]))
        ->assertSuccessful();
});
