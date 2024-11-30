<?php

namespace Payflow\Database\Factories;

use Payflow\Models\Country;
use Payflow\Models\TaxZone;
use Payflow\Models\TaxZoneCountry;

class TaxZoneCountryFactory extends BaseFactory
{
    protected $model = TaxZoneCountry::class;

    public function definition(): array
    {
        return [
            'tax_zone_id' => TaxZone::factory(),
            'country_id' => Country::factory(),
        ];
    }
}
