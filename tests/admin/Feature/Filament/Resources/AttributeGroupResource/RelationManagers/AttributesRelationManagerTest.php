<?php

use Livewire\Livewire;
use Payflow\Admin\Filament\Resources\AttributeGroupResource\Pages\EditAttributeGroup;
use Payflow\Admin\Filament\Resources\AttributeGroupResource\RelationManagers\AttributesRelationManager;
use Payflow\Models\AttributeGroup;

uses(\Payflow\Tests\Admin\Feature\Filament\TestCase::class)
    ->group('resource.attribute-group');

it('can render relation manager', function () {

    $this->asStaff();

    $attributeGroup = AttributeGroup::factory()->create();

    Livewire::test(AttributesRelationManager::class, [
        'ownerRecord' => $attributeGroup,
        'pageClass' => EditAttributeGroup::class,
    ])->assertSuccessful();
});

it('can create attributes', function ($type, $configuration = [], $expectedData = []) {

    $lang = \Payflow\Models\Language::factory()->create([
        'default' => true,
        'code' => 'en',
    ]);

    $this->asStaff();

    $attributeGroup = AttributeGroup::factory()->create();

    Livewire::test(AttributesRelationManager::class, [
        'ownerRecord' => $attributeGroup,
        'pageClass' => EditAttributeGroup::class,
    ])->callTableAction(\Filament\Actions\CreateAction::class, data: [
        'name.'.$lang->code => 'Foobar',
        'type' => $type,
        'handle' => 'foobar',
        'configuration' => $configuration,
    ])->assertHasNoTableActionErrors();

    $this->assertDatabaseHas((new \Payflow\Models\Attribute)->getTable(), [
        'attribute_group_id' => $attributeGroup->id,
        'name' => '{"en":"Foobar"}',
        'handle' => 'foobar',
        'configuration' => $expectedData,
    ]);
})->with([
    'text' => [
        \Payflow\FieldTypes\Text::class,
        ['richtext' => false],
        '{"richtext":false}',
    ],
    'richtext' => [
        \Payflow\FieldTypes\Text::class,
        ['richtext' => true],
        '{"richtext":true}',
    ],
    'dropdown' => [
        \Payflow\FieldTypes\Dropdown::class,
        [],
        '{"lookups":[]}',
    ],
    'dropdown-with-lookups' => [
        \Payflow\FieldTypes\Dropdown::class,
        ['lookups' => ['Foo' => 'foo', 'Bar' => 'bar']],
        '{"lookups":[{"label":"Foo","value":"foo"},{"label":"Bar","value":"bar"}]}',
    ],
    'number' => [
        \Payflow\FieldTypes\Number::class,
        [],
        '{"min":null,"max":null}',
    ],
    'number-with-min-max' => [
        \Payflow\FieldTypes\Number::class,
        ['min' => 5, 'max' => 10],
        '{"min":5,"max":10}',
    ],
]);
