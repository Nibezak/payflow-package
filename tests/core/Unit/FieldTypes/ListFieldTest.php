<?php

uses(\Payflow\Tests\Core\TestCase::class);
use Payflow\Exceptions\FieldTypeException;
use Payflow\FieldTypes\ListField;

test('can set value', function () {
    $field = new ListField;
    $field->setValue([
        'Foo',
    ]);

    expect($field->getValue())->toEqual(['Foo']);
});

test('can set value in constructor', function () {
    $field = new ListField([
        'Foo',
    ]);

    expect($field->getValue())->toEqual(['Foo']);
});

test('check does not allow non arrays', function () {
    $this->expectException(FieldTypeException::class);

    new ListField('Not an array');
});
