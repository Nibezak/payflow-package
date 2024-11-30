<?php

namespace Payflow\Database\Factories;

use Payflow\Models\TaxRate;
use Payflow\Models\TaxZone;

class TaxRateFactory extends BaseFactory
{
    protected $model = TaxRate::class;

    public function definition(): array
    {
        return [
            'tax_zone_id' => TaxZone::factory(),
            'name' => $this->faker->name,
            'priority' => $this->faker->numberBetween(1, 50),
        ];
    }
}
