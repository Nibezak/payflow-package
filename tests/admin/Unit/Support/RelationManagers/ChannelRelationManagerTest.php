<?php

use Livewire\Livewire;
use Payflow\Admin\Support\RelationManagers\ChannelRelationManager;

uses(\Payflow\Tests\Admin\Unit\Filament\TestCase::class)
    ->group('support.relationManagers');

it('can render relationship manager', function () {
    \Payflow\Models\CustomerGroup::factory()->create([
        'default' => true,
    ]);

    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $product = \Payflow\Models\Product::factory()->create();

    $this->asStaff(admin: true);

    Livewire::test(ChannelRelationManager::class, [
        'ownerRecord' => $product,
        'pageClass' => 'customerGroupRelationManager',
    ])->assertSuccessful();
});
