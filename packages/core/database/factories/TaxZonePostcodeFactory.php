<?php

namespace Payflow\Database\Factories;

use Payflow\Models\Country;
use Payflow\Models\TaxZone;
use Payflow\Models\TaxZonePostcode;

class TaxZonePostcodeFactory extends BaseFactory
{
    protected $model = TaxZonePostcode::class;

    public function definition(): array
    {
        return [
            'tax_zone_id' => TaxZone::factory(),
            'country_id' => Country::factory(),
            'postcode' => $this->faker->postcode,
        ];
    }
}
