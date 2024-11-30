<?php

uses(\Payflow\Tests\Admin\Unit\Livewire\TestCase::class)
    ->group('livewire.support.forms');

describe('list field converter', function () {
    beforeEach(function () {
        $this->asStaff();
    });

    test('can convert attribute to form input component', function () {
        $attribute = \Payflow\Models\Attribute::factory()->create([
            'type' => \Payflow\FieldTypes\ListField::class,
        ]);

        $inputComponent = \Payflow\Admin\Support\FieldTypes\ListField::getFilamentComponent($attribute);

        expect($inputComponent)->toBeInstanceOf(\Filament\Forms\Components\KeyValue::class);
    });
});
