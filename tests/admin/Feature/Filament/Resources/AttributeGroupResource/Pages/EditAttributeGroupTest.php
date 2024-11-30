<?php

use Livewire\Livewire;
use Payflow\Admin\Filament\Resources\AttributeGroupResource;
use Payflow\Admin\Filament\Resources\AttributeGroupResource\Pages\EditAttributeGroup;
use Payflow\Models\AttributeGroup;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.attribute-group');

it('can render attribute group edit page', function () {

    \Payflow\Models\Language::factory()->create([
        'default' => true,
    ]);

    $this->asStaff(admin: true)
        ->get(AttributeGroupResource::getUrl('edit', ['record' => AttributeGroup::factory()->create()]))
        ->assertSuccessful();
});

it('can retrieve attribute group data', function () {

    $lang = \Payflow\Models\Language::factory()->create([
        'default' => true,
        'code' => 'en',
    ]);

    $this->asStaff();

    $attributeGroup = AttributeGroup::factory()->create();

    Livewire::test(EditAttributeGroup::class, [
        'record' => $attributeGroup->getRouteKey(),
    ])
        ->assertFormSet([
            'name.'.$lang->code => $attributeGroup->translate('name', $lang->code),
        ]);
});
