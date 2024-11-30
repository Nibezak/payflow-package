<?php

namespace Payflow\Database\Factories;

use Payflow\Models\State;
use Payflow\Models\TaxZone;
use Payflow\Models\TaxZoneState;

class TaxZoneStateFactory extends BaseFactory
{
    protected $model = TaxZoneState::class;

    public function definition(): array
    {
        return [
            'tax_zone_id' => TaxZone::factory(),
            'state_id' => State::factory(),
        ];
    }
}
