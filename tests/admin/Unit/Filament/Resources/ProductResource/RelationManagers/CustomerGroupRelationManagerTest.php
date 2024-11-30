<?php

use Livewire\Livewire;
use Payflow\Admin\Filament\Resources\ProductResource\RelationManagers\CustomerGroupRelationManager;

uses(\Payflow\Tests\Admin\Unit\Filament\TestCase::class)
    ->group('resource.product');

it('can render relationship manager', function () {
    \Payflow\Models\CustomerGroup::factory()->create([
        'default' => true,
    ]);

    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $product = \Payflow\Models\Product::factory()->create();

    $this->asStaff(admin: true);

    Livewire::test(CustomerGroupRelationManager::class, [
        'ownerRecord' => $product,
        'pageClass' => 'customerGroupRelationManager',
    ])->assertSuccessful();
});
