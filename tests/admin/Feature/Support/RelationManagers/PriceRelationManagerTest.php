<?php

use Livewire\Livewire;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('support.relation-managers');

it('can render relation manager', function ($model, $page) {
    $this->asStaff();

    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $model = $model::factory()->create();

    Livewire::test(\Payflow\Admin\Support\RelationManagers\PriceRelationManager::class, [
        'ownerRecord' => $model,
        'pageClass' => $page,
    ])->assertSuccessful();
})->with([
    'product' => [
        'model' => \Payflow\Models\Product::class,
        'page' => \Payflow\Admin\Filament\Resources\ProductResource\Pages\ManageProductPricing::class,
    ],
]);
