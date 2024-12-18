<?php

namespace Payflow\Shipping\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Payflow\Shipping\Models\ShippingExclusionList;

class ShippingExclusionListFactory extends Factory
{
    protected $model = ShippingExclusionList::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
