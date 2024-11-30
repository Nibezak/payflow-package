<?php

use Livewire\Livewire;
use Payflow\Admin\Filament\Resources\StaffResource;
use Payflow\Admin\Filament\Resources\StaffResource\Pages\EditStaff;
use Payflow\Admin\Models\Staff;
use Payflow\Admin\Support\Facades\PayflowAccessControl;
use Spatie\Permission\Models\Role;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.staff');

beforeEach(fn () => $this->asStaff(admin: true));

it('can render staff edit page', function () {
    $this->get(StaffResource::getUrl('edit', ['record' => Staff::factory()->create()]))
        ->assertSuccessful();
});

it('can retrieve staff data', function () {
    $staff = Staff::factory()->create();

    Livewire::test(EditStaff::class, [
        'record' => $staff->getRouteKey(),
    ])
        ->assertFormSet([
            'firstname' => $staff->firstname,
            'lastname' => $staff->lastname,
            'email' => $staff->email,
        ]);
});

it('can save staff data', function () {
    $staff = Staff::factory()->create();

    $newData = Staff::factory()->make();

    Livewire::test(EditStaff::class, [
        'record' => $staff->getRouteKey(),
    ])
        ->fillForm([
            'firstname' => $newData->firstname,
            'lastname' => $newData->lastname,
            'email' => $newData->email,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($staff->refresh())
        ->firstname->toBe($newData->firstname)
        ->lastname->toBe($newData->lastname)
        ->email->toBe($newData->email);
});

it('can assign staff role and permissions', function () {
    $staff = Staff::factory()->create([
        'admin' => false,
    ]);

    $roles = ['staff'];
    $permissions = PayflowAccessControl::getGroupedPermissions()->random(4)->mapWithKeys(fn ($perm) => [$perm->handle => true]);
    $rolePermission = array_keys($permissions->take(1)->toArray());

    $staffRole = Role::findByName('staff');
    $staffRole->syncPermissions($rolePermission);

    Livewire::test(EditStaff::class, [
        'record' => $staff->getRouteKey(),
    ])
        ->fillForm([
            'roles' => $roles,
            'permissions' => $permissions->toArray(),
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($staff->hasExactRoles($roles))
        ->toBeTrue()
        ->and(
            $permissions->reject(fn ($val, $handle) => $handle == $rolePermission)->keys()->toArray()
        )->toEqualCanonicalizing($staff->getDirectPermissions()->pluck('name')->toArray())
        ->and($rolePermission)
        ->toEqualCanonicalizing($staff->getPermissionsViaRoles()->pluck('name')->toArray());
});
