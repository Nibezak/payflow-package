<?php

namespace Payflow\Database\Factories;

use Payflow\Models\ProductType;

class ProductTypeFactory extends BaseFactory
{
    protected $model = ProductType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
