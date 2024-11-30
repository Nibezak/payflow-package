<?php

uses(\Payflow\Tests\Admin\Unit\Livewire\TestCase::class)
    ->group('livewire.support.forms');

describe('dropdown converter', function () {
    beforeEach(function () {
        $this->asStaff();
    });

    test('can convert attribute to form input component', function () {
        $attribute = \Payflow\Models\Attribute::factory()->create([
            'type' => \Payflow\FieldTypes\Dropdown::class,
        ]);

        $inputComponent = \Payflow\Admin\Support\FieldTypes\Dropdown::getFilamentComponent($attribute);

        expect($inputComponent)->toBeInstanceOf(\Filament\Forms\Components\Select::class);
    });

    test('can render dropdown options', function () {
        $attribute = \Payflow\Models\Attribute::factory()->create([
            'type' => \Payflow\FieldTypes\Dropdown::class,
            'configuration' => [
                'lookups' => [
                    [
                        'label' => 'Foo',
                        'value' => 'bar',
                    ],
                ],
            ],
        ]);

        $inputComponent = \Payflow\Admin\Support\FieldTypes\Dropdown::getFilamentComponent($attribute);

        $options = $inputComponent->getOptions();
        expect($options)->toBeArray()
            ->toHaveKey('bar')
            ->toContain('Foo');
    });
});
