<?php

use Payflow\Admin\Filament\Resources\CustomerResource\Pages\EditCustomer;
use Payflow\Admin\Support\Facades\PayflowPanel;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('extending.edit');

it('can change data before fill', function () {
    $class = new class extends \Payflow\Admin\Support\Extending\EditPageExtension
    {
        public function beforeFill(array $data): array
        {
            $data['first_name'] = 'Jacob';

            return $data;
        }
    };

    $customer = \Payflow\Models\Customer::factory()->create([
        'first_name' => 'Geoff',
    ]);

    PayflowPanel::extensions([
        EditCustomer::class => $class::class,
    ]);

    $this->asStaff(admin: true);

    \Livewire\Livewire::test(EditCustomer::class, [
        'record' => $customer->getRouteKey(),
    ])->assertFormSet([
        'first_name' => 'Jacob',
    ])->call('save');

    $this->assertDatabaseHas(\Payflow\Models\Customer::class, [
        'first_name' => 'Jacob',
    ]);
});

it('can change data before save', function () {
    $class = new class extends \Payflow\Admin\Support\Extending\EditPageExtension
    {
        public function beforeSave(array $data): array
        {
            $data['first_name'] = 'Tony';

            return $data;
        }
    };

    $customer = \Payflow\Models\Customer::factory()->create([
        'first_name' => 'Geoff',
    ]);

    PayflowPanel::extensions([
        EditCustomer::class => $class::class,
    ]);

    $this->asStaff(admin: true);

    \Livewire\Livewire::test(EditCustomer::class, [
        'record' => $customer->getRouteKey(),
    ])->assertFormSet([
        'first_name' => 'Geoff',
    ])->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(\Payflow\Models\Customer::class, [
        'first_name' => 'Tony',
    ]);
});
