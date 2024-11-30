<?php

uses(\Payflow\Tests\Admin\Unit\Livewire\TestCase::class)
    ->group('livewire.support.forms');

describe('toggle field converter', function () {
    beforeEach(function () {
        $this->asStaff();
    });

    test('can convert attribute to form input component', function () {
        $attribute = \Payflow\Models\Attribute::factory()->create([
            'type' => \Payflow\FieldTypes\Toggle::class,
        ]);

        $inputComponent = \Payflow\Admin\Support\FieldTypes\Toggle::getFilamentComponent($attribute);

        expect($inputComponent)->toBeInstanceOf(\Filament\Forms\Components\Toggle::class);
    });
});
