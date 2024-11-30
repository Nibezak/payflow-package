<?php

uses(\Payflow\Tests\Core\TestCase::class);
use Payflow\Exceptions\FieldTypeException;
use Payflow\FieldTypes\Toggle;

test('can set value', function () {
    $field = new Toggle;
    $field->setValue(false);

    expect($field->getValue())->toEqual(false);
});

test('can set value in constructor', function () {
    $field = new Toggle(true);

    expect($field->getValue())->toEqual(true);
});

test('check it does not allow array', function () {
    $this->expectException(FieldTypeException::class);

    new Toggle(['foo']);
});
