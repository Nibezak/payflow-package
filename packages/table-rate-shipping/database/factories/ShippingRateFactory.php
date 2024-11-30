<?php

namespace Payflow\Shipping\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Payflow\Shipping\Models\ShippingRate;

class ShippingRateFactory extends Factory
{
    protected $model = ShippingRate::class;

    public function definition(): array
    {
        return [];
    }
}
