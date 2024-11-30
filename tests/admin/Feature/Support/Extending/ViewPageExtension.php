<?php

use Filament\Infolists\Infolist;
use Illuminate\Support\Str;
use Payflow\Admin\Filament\Resources\OrderResource\Pages\ManageOrder;
use Payflow\Admin\Support\Facades\PayflowPanel;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('extending.view');

beforeEach(function () {
    $this->asStaff();

    $currency = \Payflow\Models\Currency::factory()->create([
        'default' => true,
    ]);

    $country = \Payflow\Models\Country::factory()->create();

    $this->order = \Payflow\Models\Order::factory()
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

});

it('can extend Infolist', function () {
    $class = new class extends \Payflow\Admin\Support\Extending\ViewPageExtension
    {
        public function extendsInfolist(Infolist $infolist): Infolist
        {
            return $infolist->schema([
                ...$infolist->getComponents(true),
                \Filament\Infolists\Components\TextEntry::make('custom_title')
                    ->label('custom_title'),
            ]);
        }
    };

    PayflowPanel::registerExtension($class, ManageOrder::class);

    \Livewire\Livewire::test(ManageOrder::class, [
        'record' => $this->order->getRouteKey(),
    ])
        ->assertSee($this->order->reference)
        ->assertSee('custom_title');
});
