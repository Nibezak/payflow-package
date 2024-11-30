<?php

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('actions');

beforeEach(function () {
    Config::set('payflow.panel.scout_enabled', false);

    $this->asStaff(admin: true);
});

it('can render', function () {
    \Livewire\Livewire::test(Filament\Livewire\GlobalSearch::class)
        ->assertSeeHtml('search');
});

it('can search customer', function () {

    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $record = \Payflow\Models\Customer::factory()->create([
        'account_ref' => 'X67HB',
    ]);

    \Livewire\Livewire::test(Filament\Livewire\GlobalSearch::class)
        ->set('search', $record->account_ref)
        ->assertDispatched('open-global-search-results')
        ->assertSee($record->account_ref);
});

it('can search order', function () {

    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $currency = \Payflow\Models\Currency::factory()->create([
        'default' => true,
    ]);

    $country = \Payflow\Models\Country::factory()->create();

    $record = \Payflow\Models\Order::factory()
        ->for(\Payflow\Models\Customer::factory())
        ->has(\Payflow\Models\OrderAddress::factory()->state([
            'type' => 'shipping',
            'country_id' => $country->id,
        ]), 'shippingAddress')
        ->has(\Payflow\Models\OrderAddress::factory()->state([
            'type' => 'billing',
            'country_id' => $country->id,
        ]), 'billingAddress')
        ->create([
            'currency_code' => $currency->code,
            'meta' => [
                'additional_info' => Str::random(),
            ],
        ]);

    \Livewire\Livewire::test(Filament\Livewire\GlobalSearch::class)
        ->set('search', $record->reference)
        ->assertDispatched('open-global-search-results')
        ->assertSee($record->reference);
});

it('can search collection', function () {

    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $record = \Payflow\Models\Collection::factory()->create();

    \Livewire\Livewire::test(Filament\Livewire\GlobalSearch::class)
        ->set('search', $record->group->name)
        ->assertDispatched('open-global-search-results')
        ->assertSee($record->translateAttribute('name'));
});

it('can search brand', function () {
    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $brand = \Payflow\Models\Brand::factory()->create();

    \Livewire\Livewire::test(Filament\Livewire\GlobalSearch::class)
        ->set('search', $brand->name)
        ->assertDispatched('open-global-search-results')
        ->assertSee($brand->name);
});

it('can search product', function () {

    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    \Payflow\Models\Currency::factory()->create([
        'default' => true,
    ]);

    $record = \Payflow\Models\Product::factory()->create();

    \Payflow\Models\ProductVariant::factory()->create([
        'product_id' => $record->id,
    ]);

    \Livewire\Livewire::test(Filament\Livewire\GlobalSearch::class)
        ->set('search', $record->variants->first()->sku)
        ->assertDispatched('open-global-search-results')
        ->assertSee($record->variants->first()->sku);
});
