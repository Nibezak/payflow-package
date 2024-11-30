<?php

use Livewire\Livewire;

uses(\Payflow\Tests\Admin\Unit\Filament\TestCase::class)
    ->group('resource.brand');

it('can return configured relation managers', function () {
    \Payflow\Models\CustomerGroup::factory()->create([
        'default' => true,
    ]);

    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $brand = \Payflow\Models\Brand::factory()->create();

    $this->asStaff(admin: true);

    $component = Livewire::test(\Payflow\Admin\Filament\Resources\BrandResource\Pages\ManageBrandMedia::class, [
        'record' => $brand->id,
        'pageClass' => 'brandMediaRelationManager',
    ])->assertSuccessful();

    $managers = $component->instance()->getRelationManagers();

    expect($managers[0])->toBeInstanceOf(\Filament\Resources\RelationManagers\RelationGroup::class);

    expect($managers[0]->getManagers())->toHaveCount(1);
});
