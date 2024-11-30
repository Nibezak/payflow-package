<?php

namespace Payflow\Shipping\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Payflow\Shipping\Models\ShippingZonePostcode;

class ShippingZonePostcodeFactory extends Factory
{
    protected $model = ShippingZonePostcode::class;

    public function definition(): array
    {
        return [
            'postcode' => $this->faker->postcode,
        ];
    }
}
