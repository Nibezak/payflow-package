<?php

namespace Payflow\Database\Factories;

use Payflow\Models\Currency;
use Payflow\Models\Price;

class PriceFactory extends BaseFactory
{
    protected $model = Price::class;

    public function definition(): array
    {
        return [
            'price' => $this->faker->numberBetween(1, 2500),
            'compare_price' => $this->faker->numberBetween(1, 2500),
            'currency_id' => Currency::factory(),
        ];
    }
}
