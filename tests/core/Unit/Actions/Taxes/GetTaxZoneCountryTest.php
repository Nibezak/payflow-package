<?php

uses(\Payflow\Tests\Core\TestCase::class);

use Payflow\Actions\Taxes\GetTaxZoneCountry;
use Payflow\Models\Country;
use Payflow\Models\TaxZoneCountry;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class)->group('taxes');

test('can match country id', function () {
    $belgium = Country::factory()->create([
        'name' => 'Belgium',
    ]);

    $uk = Country::factory()->create([
        'name' => 'United Kingdom',
    ]);

    $taxZoneBelgium = TaxZoneCountry::factory()->create([
        'country_id' => $belgium->id,
    ]);

    $taxZoneUk = TaxZoneCountry::factory()->create([
        'country_id' => $uk->id,
    ]);

    $zone = app(GetTaxZoneCountry::class)->execute($uk->id);

    expect($zone->id)->toEqual($taxZoneUk->id);
});

test('can mismatch country id', function () {
    $belgium = Country::factory()->create([
        'name' => 'Belgium',
    ]);

    $uk = Country::factory()->create([
        'name' => 'United Kingdom',
    ]);

    $taxZoneBelgium = TaxZoneCountry::factory()->create([
        'country_id' => $belgium->id,
    ]);

    $zone = app(GetTaxZoneCountry::class)->execute($uk->id);

    expect($zone)->toBeNull();
});
