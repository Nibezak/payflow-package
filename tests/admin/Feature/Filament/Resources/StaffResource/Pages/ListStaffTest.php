<?php

use Livewire\Livewire;
// use Payflow\Admin\Filament\Resources\StaffResource;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.staff');

it('can render staff index page', function () {
    $this->asStaff(admin: true)
        // ->get(StaffResource::getUrl('index'))
        ->assertSuccessful();
});

it('can list staff', function () {
    $this->asStaff();

    // $staffs = \Payflow\Admin\Models\Staff::factory(5)->create();

    // Livewire::test(\Payflow\Admin\Filament\Resources\StaffResource\Pages\ListStaff::class)
    //     ->assertCountTableRecords(6)
    //     ->assertCanSeeTableRecords($staffs);
});
