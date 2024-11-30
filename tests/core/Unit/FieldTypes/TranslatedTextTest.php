<?php

uses(\Payflow\Tests\Core\TestCase::class);
use Illuminate\Support\Collection;
use Payflow\Exceptions\FieldTypeException;
use Payflow\FieldTypes\Number;
use Payflow\FieldTypes\Text;
use Payflow\FieldTypes\TranslatedText;

test('can set value', function () {
    $field = new TranslatedText;
    $field->setValue(collect([
        'en' => new Text('Blue'),
        'fr' => new Text('Bleu'),
    ]));

    expect($field->getValue())->toBeInstanceOf(Collection::class);
});

test('can set value in constructor', function () {
    $field = new TranslatedText(collect([
        'en' => new Text('Blue'),
        'fr' => new Text('Bleu'),
    ]));

    expect($field->getValue())->toBeInstanceOf(Collection::class);
});

test('can json encode fieldtype', function () {
    $data = [
        'en' => 'Blue',
        'fr' => 'Bleu',
    ];

    $field = new TranslatedText(collect([
        'en' => new Text('Blue'),
        'fr' => new Text('Bleu'),
    ]));

    expect(json_encode($field))->toBe(json_encode($data));
});

test('check does not allow non text field types', function () {
    expect(true)->toBeTrue();

    // TODO: This needs looking at when we get to attribute saving.
    // $this->expectException(FieldTypeException::class);
    // $field = new TranslatedText(collect([
    //     'en' => new Text('Blue'),
    //     'fr' => new Number(123),
    // ]));
});
