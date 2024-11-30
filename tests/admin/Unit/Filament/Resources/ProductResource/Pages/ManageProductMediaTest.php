<?php

use Livewire\Livewire;

uses(\Payflow\Tests\Admin\Unit\Filament\TestCase::class)
    ->group('resource.product');

it('can return configured relation managers', function () {
    \Payflow\Models\CustomerGroup::factory()->create([
        'default' => true,
    ]);

    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $product = \Payflow\Models\Product::factory()->create();

    $this->asStaff(admin: true);

    $component = Livewire::test(\Payflow\Admin\Filament\Resources\ProductResource\Pages\ManageProductMedia::class, [
        'record' => $product->id,
        'pageClass' => 'productMediaRelationManager',
    ])->assertSuccessful();

    $managers = $component->instance()->getRelationManagers();

    expect($managers[0])->toBeInstanceOf(\Filament\Resources\RelationManagers\RelationGroup::class);

    expect($managers[0]->getManagers())->toHaveCount(1);
});
