<?php

uses(\Payflow\Tests\Core\TestCase::class);
use Payflow\Actions\Taxes\GetTaxZoneState;
use Payflow\Models\State;
use Payflow\Models\TaxZoneState;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can match exact state name', function () {
    $california = State::factory()->create([
        'code' => 'CA',
        'name' => 'California',
    ]);

    $alabama = State::factory()->create([
        'code' => 'AL',
        'name' => 'Alabama',
    ]);

    TaxZoneState::factory()->create([
        'state_id' => $california->id,
    ]);

    $al = TaxZoneState::factory()->create([
        'state_id' => $alabama->id,
    ]);

    $zone = app(GetTaxZoneState::class)->execute('Alabama');

    expect($zone->id)->toEqual($al->id);
});

test('can match exact state code', function () {
    $california = State::factory()->create([
        'code' => 'CA',
        'name' => 'California',
    ]);

    $alabama = State::factory()->create([
        'code' => 'AL',
        'name' => 'Alabama',
    ]);

    TaxZoneState::factory()->create([
        'state_id' => $california->id,
    ]);

    $al = TaxZoneState::factory()->create([
        'state_id' => $alabama->id,
    ]);

    $zone = app(GetTaxZoneState::class)->execute('AL');

    expect($zone)->not->toBeNull();

    expect($zone?->id)->toEqual($al->id);
});

test('can mismatch exact state name', function () {
    $california = State::factory()->create([
        'code' => 'CA',
        'name' => 'California',
    ]);

    $alabama = State::factory()->create([
        'code' => 'AL',
        'name' => 'Alabama',
    ]);

    TaxZoneState::factory()->create([
        'state_id' => $california->id,
    ]);

    $al = TaxZoneState::factory()->create([
        'state_id' => $alabama->id,
    ]);

    $zone = app(GetTaxZoneState::class)->execute('Alaba');

    expect($zone)->toBeNull();

    $this->assertNotEquals($al->id, $zone?->id);
});
