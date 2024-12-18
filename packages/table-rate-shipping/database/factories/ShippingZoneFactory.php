<?php

namespace Payflow\Shipping\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Payflow\Shipping\Models\ShippingZone;

class ShippingZoneFactory extends Factory
{
    protected $model = ShippingZone::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'type' => 'postcodes',
        ];
    }
}
