<?php

uses(\Payflow\Tests\Core\TestCase::class);
use Illuminate\Support\Collection;
use Payflow\Base\FieldTypeManifest;
use Payflow\Base\FieldTypeManifestInterface;
use Payflow\Exceptions\FieldTypes\FieldTypeMissingException;
use Payflow\Exceptions\FieldTypes\InvalidFieldTypeException;
use Payflow\Models\Channel;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can instantiate class', function () {
    $manifest = app(FieldTypeManifestInterface::class);

    expect($manifest)->toBeInstanceOf(FieldTypeManifest::class);
});

test('can return types', function () {
    $manifest = app(FieldTypeManifestInterface::class);

    expect($manifest->getTypes())->toBeInstanceOf(Collection::class);
});

test('has base types set', function () {
    $manifest = app(FieldTypeManifestInterface::class);

    expect($manifest->getTypes())->toBeInstanceOf(Collection::class);

    expect($manifest->getTypes())->not->toBeEmpty();
});

test('cannot add non fieldtype', function () {
    $manifest = app(FieldTypeManifestInterface::class);

    $this->expectException(
        InvalidFieldTypeException::class
    );

    $manifest->add(Channel::class);

    $this->expectException(
        FieldTypeMissingException::class
    );

    $manifest->add(\Payflow\Models\Cart::class);
});
