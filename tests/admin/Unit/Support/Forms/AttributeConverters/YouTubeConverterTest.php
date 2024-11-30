
<?php

uses(\Payflow\Tests\Admin\Unit\Livewire\TestCase::class)
    ->group('livewire.support.forms');

describe('youtube field converter', function () {
    beforeEach(function () {
        $this->asStaff();
    });

    test('can convert attribute to form input component', function () {
        $attribute = \Payflow\Models\Attribute::factory()->create([
            'type' => \Payflow\FieldTypes\YouTube::class,
        ]);

        $inputComponent = \Payflow\Admin\Support\FieldTypes\YouTube::getFilamentComponent($attribute);

        expect($inputComponent)->toBeInstanceOf(\Payflow\Admin\Support\Forms\Components\YouTube::class);
    });
});
