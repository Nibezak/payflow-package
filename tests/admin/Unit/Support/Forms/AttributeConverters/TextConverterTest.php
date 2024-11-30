<?php

uses(\Payflow\Tests\Admin\Unit\Livewire\TestCase::class)
    ->group('livewire.support.forms');

describe('list field converter', function () {
    beforeEach(function () {
        $this->asStaff();
    });

    test('can convert attribute to form input component', function () {
        $attribute = \Payflow\Models\Attribute::factory()->create([
            'type' => \Payflow\FieldTypes\Text::class,
        ]);

        $inputComponent = \Payflow\Admin\Support\FieldTypes\TextField::getFilamentComponent($attribute);

        expect($inputComponent)->toBeInstanceOf(\Filament\Forms\Components\TextInput::class);
    });

    test('can return richtext component', function () {
        $attribute = \Payflow\Models\Attribute::factory()->create([
            'type' => \Payflow\FieldTypes\Text::class,
            'configuration' => [
                'richtext' => true,
            ],
        ]);

        $inputComponent = \Payflow\Admin\Support\FieldTypes\TextField::getFilamentComponent($attribute);

        expect($inputComponent)->toBeInstanceOf(\Filament\Forms\Components\RichEditor::class);
    });
});
