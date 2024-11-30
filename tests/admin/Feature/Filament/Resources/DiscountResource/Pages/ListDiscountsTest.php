<?php

use function Pest\Laravel\get;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.discount');

beforeEach(function () {
    $this->asStaff();
});

it('can list discounts', function () {
    get(
        \Payflow\Admin\Filament\Resources\DiscountResource::getUrl('index')
    )->assertSuccessful();
});

it('can create a discount', function () {
    $discount = \Payflow\Models\Discount::factory()->create();
    \Livewire\Livewire::test(
        \Payflow\Admin\Filament\Resources\DiscountResource\Pages\ListDiscounts::class
    )->callAction('create', [
        'name' => 'Discount A',
        'handle' => 'discount_a',
        'starts_at' => now(),
        'type' => \Payflow\DiscountTypes\BuyXGetY::class,
    ])->assertHasNoErrors();
});
