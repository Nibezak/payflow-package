<?php

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\get;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.discount');

beforeEach(function () {
    $this->asStaff();
});

it('can render discount edit page', function () {
    get(
        \Payflow\Admin\Filament\Resources\DiscountResource::getUrl(
            'edit',
            ['record' => \Payflow\Models\Discount::factory()->create()]
        )
    )->assertSuccessful();
});

it('can edit discount', function () {
    $discount = \Payflow\Models\Discount::factory()->create();
    \Livewire\Livewire::test(\Payflow\Admin\Filament\Resources\DiscountResource\Pages\EditDiscount::class,
        ['record' => $discount->getKey()]
    )->fillForm([
        'name' => 'Updated Name',
        'handle' => 'updated_name',
    ])->call('save')->assertHasNoErrors();

    assertDatabaseHas(\Payflow\Models\Discount::class, [
        'name' => 'Updated Name',
        'handle' => 'updated_name',
    ]);
});

it('can validate start and end date', function () {
    $discount = \Payflow\Models\Discount::factory()->create();
    \Livewire\Livewire::test(\Payflow\Admin\Filament\Resources\DiscountResource\Pages\EditDiscount::class,
        ['record' => $discount->getKey()]
    )->fillForm([
        'starts_at' => now(),
        'ends_at' => now()->subWeek(),
    ])->call('save')->assertHasFormErrors([
        'starts_at' => 'before',
    ]);
});
