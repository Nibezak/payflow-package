<?php

uses(\Payflow\Tests\Admin\Unit\Livewire\TestCase::class)
    ->group('livewire.support.forms');

describe('list field converter', function () {
    beforeEach(function () {
        $this->asStaff();
    });

    test('can convert attribute to form input component', function () {
        $attribute = \Payflow\Models\Attribute::factory()->create([
            'type' => \Payflow\FieldTypes\TranslatedText::class,
        ]);

        $inputComponent = \Payflow\Admin\Support\FieldTypes\TranslatedText::getFilamentComponent($attribute);

        expect($inputComponent)->toBeInstanceOf(\Payflow\Admin\Support\Forms\Components\TranslatedText::class);
    });
});
