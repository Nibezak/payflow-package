<?php

use Livewire\Livewire;
use Payflow\Admin\Filament\Resources\CustomerResource;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.customer');

it('can render customer index page', function () {
    $this->asStaff(admin: true)
        ->get(CustomerResource::getUrl('index'))
        ->assertSuccessful();
});

it('can list customers', function () {
    $this->asStaff();

    $customers = \Payflow\Models\Customer::factory(5)->create();

    Livewire::test(\Payflow\Admin\Filament\Resources\CustomerResource\Pages\ListCustomers::class)
        ->assertCountTableRecords(5)
        ->assertCanSeeTableRecords($customers);
});
