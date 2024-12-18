<?php

use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Payflow\Admin\Filament\Resources\StaffResource;
use Payflow\Admin\Filament\Resources\StaffResource\Pages\CreateStaff;
use Payflow\Admin\Models\Staff;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.staff');

beforeEach(fn () => $this->asStaff(admin: true));

it('can render staff create page', function () {
    $this->get(StaffResource::getUrl('create'))
        ->assertSuccessful();
});

it('can create staff', function () {
    $staff = Staff::factory()->make();

    $staff->assignRole('staff');

    $formData = [
        'firstname' => $staff->firstname,
        'lastname' => $staff->lastname,
        'email' => 'testpayer@example.com',
        'password' => 'password',
    ];

    Livewire::test(CreateStaff::class)
        ->fillForm($formData)
        ->call('create')
        ->assertHasNoFormErrors();

    unset($formData['password']);

    $record = Staff::where('email', $formData['email'])->first();

    expect(Hash::check('password', $record->password))->toBeTrue();

    $this->assertDatabaseHas(Staff::class, $formData);
});
