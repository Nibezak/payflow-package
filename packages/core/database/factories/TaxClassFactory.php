<?php

namespace Payflow\Database\Factories;

use Payflow\Models\TaxClass;

class TaxClassFactory extends BaseFactory
{
    protected $model = TaxClass::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'default' => false,
        ];
    }
}
