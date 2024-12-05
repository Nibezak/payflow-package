<?php

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.collection');

it('can render the brand collections page', function () {
    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $record = \Payflow\Models\Brand::factory()->create();

    $this->asStaff(admin: true)
        ->get(\Payflow\Admin\Filament\Resources\BrandResource::getUrl('collections', [
            'record' => $record,
        ]))
        ->assertSuccessful();
});
