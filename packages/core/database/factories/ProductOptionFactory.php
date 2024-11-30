<?php

namespace Payflow\Database\Factories;

use Illuminate\Support\Str;
use Payflow\Models\ProductOption;

class ProductOptionFactory extends BaseFactory
{
    private static $position = 1;

    protected $model = ProductOption::class;

    public function definition(): array
    {
        $name = $this->faker->name;

        return [
            'handle' => Str::slug($name),
            'name' => [
                'en' => $name,
            ],
            'label' => [
                'en' => $name,
            ],
            'shared' => true,
        ];
    }
}
