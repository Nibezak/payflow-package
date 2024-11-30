<?php

namespace Payflow\Database\Factories;

use Payflow\Models\Brand;

class BrandFactory extends BaseFactory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
