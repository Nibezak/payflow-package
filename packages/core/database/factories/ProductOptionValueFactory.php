<?php

namespace Payflow\Database\Factories;

use Payflow\Models\ProductOptionValue;

class ProductOptionValueFactory extends BaseFactory
{
    protected $model = ProductOptionValue::class;

    public function definition(): array
    {
        return [
            'name' => [
                'en' => $this->faker->name,
            ],
        ];
    }
}
