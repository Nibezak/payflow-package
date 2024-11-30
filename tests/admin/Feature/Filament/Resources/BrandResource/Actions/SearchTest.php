<?php

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.brand.search');

it('can search brand by name on brand list', function () {

    Config::set('payflow.panel.scout_enabled', false);

    $this->asStaff(admin: true);

    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $brands = \Payflow\Models\Brand::factory()->count(10)->create();

    $name = $brands->first()->name;

    \Livewire\Livewire::test(Payflow\Admin\Filament\Resources\BrandResource\Pages\ListBrands::class)
        ->searchTable($name)
        ->assertCanSeeTableRecords($brands->where('name', $name));
});
