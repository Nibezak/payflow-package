<?php

uses(\Payflow\Tests\Admin\Unit\Livewire\TestCase::class)
    ->group('livewire.support');

describe('attribute data test', function () {
    beforeEach(function () {
        $this->asStaff();
    });

    test('correct form components are returned', function ($fieldType, $expectedComponent, $configuration = []) {
        $attribute = \Payflow\Models\Attribute::factory()->create([
            'type' => $fieldType,
            'configuration' => $configuration,
        ]);

        $inputComponent = \Payflow\Admin\Support\Facades\AttributeData::getFilamentComponent($attribute);

        expect($inputComponent)->toBeInstanceOf($expectedComponent);

    })->with([
        [\Payflow\FieldTypes\Text::class, \Filament\Forms\Components\TextInput::class],
        [\Payflow\FieldTypes\Text::class, \Filament\Forms\Components\RichEditor::class, ['richtext' => true]],
        [\Payflow\FieldTypes\Dropdown::class, \Filament\Forms\Components\Select::class],
        [\Payflow\FieldTypes\ListField::class, \Filament\Forms\Components\KeyValue::class],
        [\Payflow\FieldTypes\YouTube::class, \Payflow\Admin\Support\Forms\Components\YouTube::class],
        [\Payflow\FieldTypes\Number::class, \Filament\Forms\Components\TextInput::class],
    ]);

    test('can extend converters', function () {
        $attribute = \Payflow\Models\Attribute::factory()->create([
            'type' => TestFieldType::class,
        ]);

        \Payflow\Admin\Support\Facades\AttributeData::registerFieldType(TestFieldType::class, TestFieldConverter::class);

        $inputComponent = \Payflow\Admin\Support\Facades\AttributeData::getFilamentComponent($attribute);
        expect($inputComponent)->toBeInstanceOf(\Filament\Forms\Components\RichEditor::class);
    });
});

class TestFieldType extends Payflow\FieldTypes\Text {}

class TestFieldConverter extends \Payflow\Admin\Support\FieldTypes\TextField
{
    public static function getFilamentComponent(Payflow\Models\Attribute $attribute): Filament\Forms\Components\Component
    {
        return \Filament\Forms\Components\RichEditor::make($attribute->handle);
    }
}
